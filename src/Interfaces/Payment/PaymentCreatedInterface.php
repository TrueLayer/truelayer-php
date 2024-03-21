<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Payment;

use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Exceptions\SignerException;
use TrueLayer\Interfaces\ArrayableInterface;
use TrueLayer\Interfaces\HppInterface;
use TrueLayer\Interfaces\Payment\AuthorizationFlow\AuthorizationFlowAuthorizingInterface;

interface PaymentCreatedInterface extends ArrayableInterface
{
    /**
     * @return string
     */
    public function getId(): string;

    /**
     * @return string
     */
    public function getResourceToken(): string;

    /**
     * @return string
     */
    public function getUserId(): string;

    /**
     * @return HppInterface
     */
    public function hostedPaymentsPage(): HppInterface;

    /**
     * @param string $returnUri
     *
     * @return AuthorizationFlowAuthorizingInterface
     *
     * @throws InvalidArgumentException
     * @throws SignerException
     * @throws ApiRequestJsonSerializationException
     *
     * @throws ApiResponseUnsuccessfulException
     * @deprecated
     */
    public function startAuthorization(string $returnUri): AuthorizationFlowAuthorizingInterface;

    /**
     * @return StartAuthorizationFlowRequestInterface
     *
     * @throws InvalidArgumentException
     */
    public function authorizationFlow(): StartAuthorizationFlowRequestInterface;

    /**
     * @return PaymentRetrievedInterface
     * @throws ApiRequestJsonSerializationException
     *
     * @throws ApiResponseUnsuccessfulException
     */
    public function getDetails(): PaymentRetrievedInterface;
}
