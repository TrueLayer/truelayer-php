<?php

declare(strict_types=1);

namespace TrueLayer\Services\Auth;

use Illuminate\Contracts\Validation\Factory as ValidatorFactory;
use Illuminate\Support\Carbon;
use Psr\SimpleCache\CacheInterface;
use TrueLayer\Constants\CacheKeys;
use TrueLayer\Contracts\Api\ApiClientInterface;
use TrueLayer\Contracts\Auth\AccessTokenInterface;
use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Exceptions\ValidationException;
use TrueLayer\Traits\HasAttributes;
use TrueLayer\Services\Api\AccessTokenApi;

final class AccessToken implements AccessTokenInterface
{
    /**
     * @var ApiClientInterface
     */
    private ApiClientInterface $api;

    /**
     * @var CacheInterface|null
     */
    private ?CacheInterface $cache;

    /**
     * @var ValidatorFactory
     */
    private ValidatorFactory $validatorFactory;

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
     * @param ApiClientInterface $api
     * @param ?CacheInterface $cache
     * @param ValidatorFactory   $validatorFactory
     * @param string             $clientId
     * @param string             $clientSecret
     * @param array<string>      $scopes
     */
    public function __construct(ApiClientInterface $api, ?CacheInterface $cache, ValidatorFactory $validatorFactory, string $clientId, string $clientSecret, ?array $scopes = [])
    {
        $this->api = $api;
        $this->cache = $cache;
        $this->validatorFactory = $validatorFactory;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->scopes = $scopes;
    }

    /**
     * @return string|null
     * @throws ApiRequestJsonSerializationException
     * @throws ApiResponseUnsuccessfulException
     * @throws ValidationException
     * @throws ApiRequestJsonSerializationException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function getAccessToken(): ?string
    {
        if (!$this->accessToken) {
            if ($this->cache && $this->cache->has(CacheKeys::AUTH_TOKEN)) {
                $data = unserialize($this->cache->get(CacheKeys::AUTH_TOKEN));

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
     * @throws ValidationException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    private function retrieve(): void
    {
        /** @var array{access_token: string, expires_in: int} $data */
        $data = (new AccessTokenApi($this->api))->fetch($this->clientId, $this->clientSecret, $this->scopes);
        $this->validate($data);

        $this->accessToken = $data['access_token'];
        $this->expiresIn = $data['expires_in'];
        $this->retrievedAt = (int) Carbon::now()->timestamp;

        if ($this->cache) {
            $this->cache->set(CacheKeys::AUTH_TOKEN, serialize($this->toArray()), $this->getExpiresIn());
        }
    }

    /**
     * @param mixed[] $data
     *
     * @throws ValidationException
     */
    private function validate(array $data): void
    {
        $validator = $this->validatorFactory->make($data, [
            'access_token' => 'required|string',
            'expires_in' => 'required|int',
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
            'access_token' => $this->accessToken,
            'expires_in'   => $this->expiresIn,
            'retrieved_at' => $this->retrievedAt,
            'scopes'       => $this->scopes,
        ];
    }
}
