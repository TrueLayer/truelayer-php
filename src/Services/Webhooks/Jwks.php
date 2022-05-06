<?php

declare(strict_types=1);

namespace TrueLayer\Services\Webhooks;

use Illuminate\Contracts\Validation\Factory as ValidatorFactory;
use Illuminate\Support\Carbon;
use TrueLayer\Constants\CacheKeys;
use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\SignerException;
use TrueLayer\Exceptions\ValidationException;
use TrueLayer\Interfaces\ApiClient\ApiClientInterface;
use TrueLayer\Interfaces\EncryptedCacheInterface;
use TrueLayer\Interfaces\Webhooks\JwksInterface;
use TrueLayer\Services\Api\WebhooksApi;

class Jwks implements JwksInterface
{
    private const CACHE_TTL = 1800;

    private ApiClientInterface $api;

    private ?EncryptedCacheInterface $cache;

    private ValidatorFactory $validatorFactory;

    private ?array $keys = null;

    private ?int $retrievedAt = null;

    public function __construct(ApiClientInterface $api,
                                ?EncryptedCacheInterface $cache,
                                ValidatorFactory $validatorFactory)
    {
        $this->api = $api;
        $this->cache = $cache;
        $this->validatorFactory = $validatorFactory;
    }

    /**
     * @throws ApiResponseUnsuccessfulException
     * @throws SignerException
     * @throws ValidationException
     * @throws ApiRequestJsonSerializationException
     *
     * @return array
     */
    public function getJsonKeys(): array
    {
        if (!$this->keys) {
            if ($this->cache && $this->cache->has(CacheKeys::JWKS_KEYS)) {
                $data = $this->cache->get(CacheKeys::JWKS_KEYS);

                $this->keys = $data['keys'];
                $this->retrievedAt = $data['retrieved_at'];
            } else {
                $this->retrieve();
            }
        }

        if ($this->isExpired()) {
            $this->retrieve();
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
     * @return JwksInterface
     */
    public function clear(): JwksInterface
    {
        $this->keys = null;
        $this->retrievedAt = null;

        return $this;
    }

    /**
     * @throws ApiResponseUnsuccessfulException
     * @throws ApiRequestJsonSerializationException
     * @throws SignerException
     * @throws ValidationException
     */
    private function retrieve(): void
    {
        $data = (new WebhooksApi($this->api))->jwks();
        $this->validate($data);

        $this->keys = $data['keys'];
        $this->retrievedAt = Carbon::now()->timestamp;

        if ($this->cache) {
            $this->cache->set(CacheKeys::JWKS_KEYS, $this->toArray(), self::CACHE_TTL);
        }
    }

    /**
     * @param array $data
     *
     * @throws ValidationException
     */
    private function validate(array $data): void
    {
        $validator = $this->validatorFactory->make($data, [
            'keys.*.kty' => 'required|string|in:RSA,EC',
            'keys.*.alg' => 'required|string|in:RS512,EC512',
            'keys.*.kid' => 'required|string',
        ]);

        try {
            $validator->validate();
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw new ValidationException($validator);
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
