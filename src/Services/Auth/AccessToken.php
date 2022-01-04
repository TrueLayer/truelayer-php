<?php

declare(strict_types=1);

namespace TrueLayer\Services\Auth;

use Illuminate\Contracts\Validation\Factory as ValidatorFactory;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Carbon;
use TrueLayer\Contracts\Api\ApiClientInterface;
use TrueLayer\Contracts\Auth\AccessTokenInterface;
use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Services\Auth\AccessTokenApi;
use TrueLayer\Traits\HasAttributes;

final class AccessToken implements AccessTokenInterface
{
    use HasAttributes;

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
     * @param ApiClientInterface $api
     * @param ValidatorFactory $validatorFactory
     * @param string $clientId
     * @param string $clientSecret
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
     *
     * @return string
     */
    public function getAccessToken(): string
    {
        if (!$this->get('access_token') || $this->isExpired()) {
            $this->retrieve();
        }

        return $this->get('access_token');
    }

    /**
     * @return int|null
     */
    public function getRetrievedAt(): ?int
    {
        return $this->get('retrieved_at');
    }

    /**
     * @return int|null
     */
    public function getExpiresIn(): ?int
    {
        return $this->get('expires_in');
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
     */
    private function retrieve(): void
    {
        $data = (new AccessTokenApi())->fetch($this->api, $this->clientId, $this->clientSecret);
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
            'retrieved_at' => 'required|int'
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
