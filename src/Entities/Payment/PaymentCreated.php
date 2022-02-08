<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payment;

use TrueLayer\Entities\Entity;
use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Exceptions\SignerException;
use TrueLayer\Exceptions\ValidationException;
use TrueLayer\Interfaces\HppInterface;
use TrueLayer\Interfaces\Payment\AuthorizationFlow\AuthorizationFlowAuthorizingInterface;
use TrueLayer\Interfaces\Payment\PaymentCreatedInterface;
use TrueLayer\Interfaces\Payment\PaymentRetrievedInterface;

final class PaymentCreated extends Entity implements PaymentCreatedInterface
{
    /**
     * @var string
     */
    protected string $id;

    /**
     * @var string
     */
    protected string $resourceToken;

    /**
     * @var string
     */
    protected string $userId;

    /**
     * @var string[]
     */
    protected array $arrayFields = [
        'id',
        'resource_token',
        'user.id' => 'user_id',
    ];

    /**
     * @var string[]
     */
    protected array $rules = [
        'id' => 'required|string',
        'user.id' => 'required|string',
        'resource_token' => 'required|string',
    ];

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getResourceToken(): string
    {
        return $this->resourceToken;
    }

    /**
     * @return string
     */
    public function getUserId(): string
    {
        return $this->userId;
    }

    /**
     * @throws ValidationException
     * @throws InvalidArgumentException
     *
     * @return HppInterface
     */
    public function hostedPaymentsPage(): HppInterface
    {
        return $this->make(HppInterface::class)
            ->paymentId($this->getId())
            ->resourceToken($this->getResourceToken());
    }

    /**
     * @param string $returnUri
     *
     * @throws ApiRequestJsonSerializationException
     * @throws ApiResponseUnsuccessfulException
     * @throws InvalidArgumentException
     * @throws SignerException
     * @throws ValidationException
     *
     * @return AuthorizationFlowAuthorizingInterface
     */
    public function startAuthorization(string $returnUri): AuthorizationFlowAuthorizingInterface
    {
        $data = $this->apiFactory()->paymentsApi()->startAuthorizationFlow($this->getId(), $returnUri);

        return $this->make(AuthorizationFlowAuthorizingInterface::class, $data);
    }

    /**
     * @throws ApiRequestJsonSerializationException
     * @throws ApiResponseUnsuccessfulException
     * @throws InvalidArgumentException
     * @throws SignerException
     * @throws ValidationException
     *
     * @return PaymentRetrievedInterface
     */
    public function getDetails(): PaymentRetrievedInterface
    {
        $data = $this->apiFactory()->paymentsApi()->retrieve($this->getId());

        return $this->make(PaymentRetrievedInterface::class, $data);
    }
}
