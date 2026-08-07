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

/**
 * The newly created payment, as returned by the payment creation response.
 */
interface PaymentCreatedInterface extends ArrayableInterface
{
    /**
     * The unique ID of the payment.
     *
     * @return string
     */
    public function getId(): string;

    /**
     * A payment token in JWT format, with a limited scope that is authorised to be used with this single
     * payment.
     *
     * @return string
     */
    public function getResourceToken(): string;

    /**
     * The unique ID of the user. If a user ID wasn't passed in the request body while creating the
     * payment, TrueLayer generates one and returns it here.
     *
     * @return string
     */
    public function getUserId(): string;

    /**
     * The Hosted Payments Page URI, if a hosted_page was requested on creation.
     *
     * @return string|null
     */
    public function getHostedPageUri(): ?string;

    /**
     * Builds the Hosted Payments Page URL for this payment.
     *
     * @return HppInterface
     */
    public function hostedPaymentsPage(): HppInterface;

    /**
     * @param string $returnUri
     *
     * @throws InvalidArgumentException
     * @throws SignerException
     * @throws ApiRequestJsonSerializationException
     * @throws ApiResponseUnsuccessfulException
     *
     * @return AuthorizationFlowAuthorizingInterface
     *
     * @deprecated
     */
    public function startAuthorization(string $returnUri): AuthorizationFlowAuthorizingInterface;

    /**
     * Starts building a request to manually start the authorization flow for this payment.
     *
     * @throws InvalidArgumentException
     *
     * @return StartAuthorizationFlowRequestInterface
     */
    public function authorizationFlow(): StartAuthorizationFlowRequestInterface;

    /**
     * Retrieves the full payment details, equivalent to calling `getPayment()` on the client with this
     * payment's ID.
     *
     * @throws ApiRequestJsonSerializationException
     * @throws ApiResponseUnsuccessfulException
     *
     * @return PaymentRetrievedInterface
     */
    public function getDetails(): PaymentRetrievedInterface;
}
