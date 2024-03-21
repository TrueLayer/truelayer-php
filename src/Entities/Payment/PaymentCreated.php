<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payment;

use TrueLayer\Entities\Entity;
use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Exceptions\SignerException;
use TrueLayer\Interfaces\HasApiFactoryInterface;
use TrueLayer\Interfaces\HppInterface;
use TrueLayer\Interfaces\Payment\AuthorizationFlow\AuthorizationFlowAuthorizingInterface;
use TrueLayer\Interfaces\Payment\PaymentCreatedInterface;
use TrueLayer\Interfaces\Payment\PaymentRetrievedInterface;
use TrueLayer\Interfaces\Payment\StartAuthorizationFlowRequestInterface;
use TrueLayer\Traits\ProvidesApiFactory;

final class PaymentCreated extends Entity implements PaymentCreatedInterface, HasApiFactoryInterface
{
    use ProvidesApiFactory;

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
     * @return HppInterface
     * @throws InvalidArgumentException
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
     * @return AuthorizationFlowAuthorizingInterface
     *
     * @throws SignerException
     * @throws ApiRequestJsonSerializationException
     * @throws ApiResponseUnsuccessfulException
     *
     * @throws InvalidArgumentException
     * @deprecated
     */
    public function startAuthorization(string $returnUri): AuthorizationFlowAuthorizingInterface
    {
        $data = $this->getApiFactory()->paymentsApi()->startAuthorizationFlow($this->getId(), [
            'provider_selection' => (object)[],
            'redirect' => ['return_uri' => $returnUri],
        ]);

        return $this->make(AuthorizationFlowAuthorizingInterface::class, $data);
    }

    /**
     * @return StartAuthorizationFlowRequestInterface
     *
     * @throws InvalidArgumentException
     */
    public function authorizationFlow(): StartAuthorizationFlowRequestInterface
    {
        return $this->make(StartAuthorizationFlowRequestInterface::class)
            ->paymentId($this->getId());
    }

    /**
     * @return PaymentRetrievedInterface
     * @throws SignerException
     * @throws ApiRequestJsonSerializationException
     * @throws ApiResponseUnsuccessfulException
     *
     * @throws InvalidArgumentException
     */
    public function getDetails(): PaymentRetrievedInterface
    {
        $data = $this->getApiFactory()->paymentsApi()->retrieve($this->getId());

        return $this->make(PaymentRetrievedInterface::class, $data);
    }
}
