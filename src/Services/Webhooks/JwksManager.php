<?php

declare(strict_types=1);

namespace TrueLayer\Services\Webhooks;

use Illuminate\Support\Carbon;
use TrueLayer\Constants\CacheKeys;
use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\SignerException;
use TrueLayer\Exceptions\TLPublicKeysNotFound;
use TrueLayer\Interfaces\ApiClient\ApiClientInterface;
use TrueLayer\Interfaces\EncryptedCacheInterface;
use TrueLayer\Interfaces\Webhook\JwksManagerInterface;
use TrueLayer\Services\Api\WebhooksApi;

class JwksManager implements JwksManagerInterface
{
    private const CACHE_TTL = 1800;

    /**
     * @var ApiClientInterface
     */
    private ApiClientInterface $api;

    /**
     * @var EncryptedCacheInterface|null
     */
    private ?EncryptedCacheInterface $cache;

    /**
     * @var mixed[]|null
     */
    private ?array $keys = null;

    /**
     * @var int|null
     */
    private ?int $retrievedAt = null;

    /**
     * @param ApiClientInterface           $api
     * @param EncryptedCacheInterface|null $cache
     */
    public function __construct(ApiClientInterface $api, ?EncryptedCacheInterface $cache)
    {
        $this->api = $api;
        $this->cache = $cache;
    }

    /**
     * @throws ApiRequestJsonSerializationException
     * @throws ApiResponseUnsuccessfulException
     * @throws TLPublicKeysNotFound
     * @throws SignerException
     *
     * @return mixed[]
     */
    public function getJsonKeys(): array
    {
        if (!$this->keys && $this->cache && $this->cache->has(CacheKeys::JWKS_KEYS)) {
            $data = $this->cache->get(CacheKeys::JWKS_KEYS);

            if (\is_array($data)) {
                $this->keys = isset($data['keys']) && \is_array($data['keys']) ? $data['keys'] : null;
                $this->retrievedAt = isset($data['retrieved_at']) && \is_int($data['retrieved_at']) ? $data['retrieved_at'] : null;
            }
        }

        if (!$this->keys || $this->isExpired()) {
            $this->retrieve();
        }

        if (!$this->keys) {
            throw new TLPublicKeysNotFound();
        }

        return $this->keys;
    }

    /**
     * @return int|null
     */
    public function getRetrievedAt(): ?int
    {
        return $this->retrievedAt ?? null;
    }

    /**
     * @return bool
     */
    public function isExpired(): bool
    {
        if (!$this->getRetrievedAt()) {
            return true;
        }

        return $this->getRetrievedAt() + self::CACHE_TTL <= Carbon::now()->timestamp;
    }

    /**
     * @return bool
     */
    public function hasCache(): bool
    {
        return (bool) $this->cache;
    }

    /**
     * @return JwksManagerInterface
     */
    public function clear(): JwksManagerInterface
    {
        $this->keys = null;
        $this->retrievedAt = null;

        return $this;
    }

    /**
     * @throws ApiResponseUnsuccessfulException
     * @throws ApiRequestJsonSerializationException
     * @throws SignerException
     */
    public function retrieve(): void
    {
        $data = (new WebhooksApi($this->api))->jwks();

        $this->keys = (array) $data['keys'];
        $this->retrievedAt = (int) Carbon::now()->timestamp;

        if ($this->cache) {
            $this->cache->set(CacheKeys::JWKS_KEYS, $this->toArray(), self::CACHE_TTL);
        }
    }

    /**
     * @return mixed[]
     */
    private function toArray(): array
    {
        return [
            'keys' => $this->keys,
            'retrieved_at' => $this->retrievedAt,
        ];
    }
}
