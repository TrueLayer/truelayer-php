<?php

declare(strict_types=1);

namespace TrueLayer\Services\Auth;

use Carbon\Carbon;
use TrueLayer\Constants\CacheKeys;
use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\SignerException;
use TrueLayer\Interfaces\ApiClient\ApiClientInterface;
use TrueLayer\Interfaces\Auth\AccessTokenInterface;
use TrueLayer\Interfaces\EncryptedCacheInterface;
use TrueLayer\Services\Api\AccessTokenApi;

final class AccessToken implements AccessTokenInterface
{
    /**
     * @var ApiClientInterface
     */
    private ApiClientInterface $api;

    /**
     * @var EncryptedCacheInterface|null
     */
    private ?EncryptedCacheInterface $cache;

    /**
     * @var string
     */
    private string $clientId;

    /**
     * @var string
     */
    private string $clientSecret;

    /**
     * @var string[]
     */
    private array $scopes = [];

    /**
     * @var string|null
     */
    private ?string $accessToken = null;

    /**
     * @var int|null
     */
    private ?int $expiresIn = null;

    /**
     * @var int|null
     */
    private ?int $retrievedAt = null;

    /**
     * @var string|null
     */
    private ?string $cacheSuffix = null;

    /**
     * @param ApiClientInterface           $api
     * @param EncryptedCacheInterface|null $cache
     * @param string                       $clientId
     * @param string                       $clientSecret
     * @param array<string>|null           $scopes
     */
    public function __construct(ApiClientInterface $api,
        ?EncryptedCacheInterface $cache,
        string $clientId,
        string $clientSecret,
        ?array $scopes = [])
    {
        $this->api = $api;
        $this->cache = $cache;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->scopes = $scopes ?? [];
    }

    /**
     * @throws ApiRequestJsonSerializationException
     * @throws ApiResponseUnsuccessfulException
     *
     * @return string|null
     */
    public function getAccessToken(): ?string
    {
        if (!$this->accessToken) {
            if ($this->cache && $this->cache->has($this->getCacheKey())) {
                /** @var array{access_token: string, expires_in: int, retrieved_at: int} $data */
                $data = $this->cache->get($this->getCacheKey());

                $this->accessToken = $data['access_token'];
                $this->expiresIn = $data['expires_in'];
                $this->retrievedAt = $data['retrieved_at'];
            } else {
                $this->retrieve();
            }
        }

        if ($this->isExpired()) {
            $this->retrieve();
        }

        return $this->accessToken;
    }

    /**
     * @return int|null
     */
    public function getRetrievedAt(): ?int
    {
        return $this->retrievedAt ?? null;
    }

    /**
     * @return int|null
     */
    public function getExpiresIn(): ?int
    {
        return $this->expiresIn ?? null;
    }

    /**
     * @param int $safetyMargin
     *
     * @return bool
     */
    public function isExpired(int $safetyMargin = 10): bool
    {
        if (!$this->getRetrievedAt() || !$this->getExpiresIn()) {
            return true;
        }

        return $this->getRetrievedAt() + $this->getExpiresIn() - $safetyMargin <= Carbon::now()->timestamp;
    }

    /**
     * @return AccessTokenInterface
     */
    public function clear(): AccessTokenInterface
    {
        $this->accessToken = null;
        $this->expiresIn = null;
        $this->retrievedAt = null;

        return $this;
    }

    /**
     * @throws ApiRequestJsonSerializationException
     * @throws ApiResponseUnsuccessfulException
     * @throws SignerException
     */
    private function retrieve(): void
    {
        /** @var array{access_token: string, expires_in: int} $data */
        $data = (new AccessTokenApi($this->api))->retrieve($this->clientId, $this->clientSecret, $this->scopes);

        $this->accessToken = $data['access_token'];
        $this->expiresIn = $data['expires_in'];
        $this->retrievedAt = (int) Carbon::now()->timestamp;

        if ($this->cache) {
            $this->cache->set($this->getCacheKey(), $this->toArray(), $this->getExpiresIn());
        }
    }

    private function getCacheKey()
    {
        return CacheKeys::AUTH_TOKEN . ':' . $this->getCacheSuffix();
    }

    private function getCacheSuffix()
    {
        if (!$this->cacheSuffix) {
            $this->cacheSuffix = \hash_hmac('sha256', \implode(',', [$this->clientId, ...$this->scopes]), $this->clientSecret);
        }

        return $this->cacheSuffix;
    }

    /**
     * @return mixed[]
     */
    private function toArray(): array
    {
        return [
            'access_token' => $this->accessToken,
            'expires_in' => $this->expiresIn,
            'retrieved_at' => $this->retrievedAt,
            'scopes' => $this->scopes,
        ];
    }
}
