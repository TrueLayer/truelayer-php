<?php

declare(strict_types=1);

namespace TrueLayer\Services\Auth;

use Illuminate\Contracts\Validation\Factory as ValidatorFactory;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Carbon;
use TrueLayer\Contracts\Api\ApiClientInterface;
use TrueLayer\Contracts\Auth\AccessTokenInterface;
use TrueLayer\Contracts\Sdk\SdkCacheInterface;
use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Exceptions\ValidationException;
use TrueLayer\Traits\HasAttributes;

final class AccessToken implements AccessTokenInterface
{
    use HasAttributes;

    /**
     * @var ApiClientInterface
     */
    private ApiClientInterface $api;

    /**
     * @var SdkCacheInterface|null
     */
    private ?SdkCacheInterface $cache;

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
     * @param ApiClientInterface $api
     * @param ?SdkCacheInterface $cache
     * @param ValidatorFactory   $validatorFactory
     * @param string             $clientId
     * @param string             $clientSecret
     * @param array<string>      $scopes
     */
    public function __construct(ApiClientInterface $api, ?SdkCacheInterface $cache, ValidatorFactory $validatorFactory, string $clientId, string $clientSecret, ?array $scopes = [])
    {
        $this->api = $api;
        $this->cache = $cache;
        $this->validatorFactory = $validatorFactory;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->scopes = $scopes;
    }

    /**
     * @throws ApiRequestJsonSerializationException
     * @throws ApiResponseUnsuccessfulException
     * @throws InvalidArgumentException
     * @throws ValidationException
     *
     * @return string
     */
    public function getAccessToken(): string
    {
        if (!$this->getNullableString('access_token') || $this->isExpired()) {
            $this->retrieve();
        }

        return $this->getString('access_token');
    }

    /**
     * @return int|null
     */
    public function getRetrievedAt(): ?int
    {
        return $this->getNullableInt('retrieved_at');
    }

    /**
     * @return int|null
     */
    public function getExpiresIn(): ?int
    {
        return $this->getNullableInt('expires_in');
    }

    /**
     * @param int $safetyMargin
     *
     * @return bool
     */
    public function isExpired(int $safetyMargin = 10): bool
    {
        return $this->getRetrievedAt() + $this->getExpiresIn() - $safetyMargin <= Carbon::now()->timestamp;
    }

    /**
     * @return AccessTokenInterface
     */
    public function clear(): AccessTokenInterface
    {
        $this->data = [];

        return $this;
    }

    /**
     * @throws ApiRequestJsonSerializationException
     * @throws ApiResponseUnsuccessfulException
     * @throws ValidationException
     */
    private function retrieve(): void
    {
        $data = (new AccessTokenApi($this->api))->fetch($this->clientId, $this->clientSecret, $this->scopes);
        $data['retrieved_at'] = Carbon::now()->timestamp;
        $this->fill($data);
    }

    /**
     * @return string[]
     */
    private function rules(): array
    {
        return [
            'access_token' => 'required|string',
            'expires_in' => 'required|int',
            'retrieved_at' => 'required|int',
        ];
    }

    /**
     * @return Validator
     */
    private function validator(): Validator
    {
        return $this->validatorFactory->make($this->data, $this->rules());
    }
}
