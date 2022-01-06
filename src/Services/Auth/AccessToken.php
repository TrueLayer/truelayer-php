<?php

declare(strict_types=1);

namespace TrueLayer\Services\Auth;

use Illuminate\Contracts\Validation\Factory as ValidatorFactory;
use Illuminate\Support\Carbon;
use TrueLayer\Contracts\Api\ApiClientInterface;
use TrueLayer\Contracts\Auth\AccessTokenInterface;
use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\ValidationException;
use TrueLayer\Services\Api\AccessTokenApi;

final class AccessToken implements AccessTokenInterface
{
    /**
     * @var ApiClientInterface
     */
    private ApiClientInterface $api;

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
     * @param ValidatorFactory   $validatorFactory
     * @param string             $clientId
     * @param string             $clientSecret
     */
    public function __construct(ApiClientInterface $api, ValidatorFactory $validatorFactory, string $clientId, string $clientSecret)
    {
        $this->api = $api;
        $this->validatorFactory = $validatorFactory;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
    }

    /**
     * @throws ApiRequestJsonSerializationException
     * @throws ApiResponseUnsuccessfulException
     * @throws ValidationException
     *
     * @return string|null
     */
    public function getAccessToken(): ?string
    {
        if (!$this->accessToken || $this->isExpired()) {
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
     */
    private function retrieve(): void
    {
        /** @var array{access_token: string, expires_in: int} $data */
        $data = (new AccessTokenApi())->fetch($this->api, $this->clientId, $this->clientSecret);
        $this->validate($data);

        $this->accessToken = $data['access_token'];
        $this->expiresIn = $data['expires_in'];
        $this->retrievedAt = (int) Carbon::now()->timestamp;
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
}
