<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Payment;

use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Exceptions\SignerException;
use TrueLayer\Interfaces\Payment\AuthorizationFlow\ConfigurationInterface;

/**
 * A settled payment, i.e. the funds have settled into the beneficiary account. Only reachable for payments
 * into merchant accounts.
 */
interface PaymentSettledInterface extends PaymentRetrievedInterface
{
    /**
     * Information about the source of funds, if available from the remitter's bank.
     *
     * @return PaymentSourceInterface
     */
    public function getPaymentSource(): PaymentSourceInterface;

    /**
     * The date and time the payment settled.
     *
     * @return \DateTimeInterface
     */
    public function getSettledAt(): \DateTimeInterface;

    /**
     * The date and time the payment executed.
     *
     * @return \DateTimeInterface
     */
    public function getExecutedAt(): \DateTimeInterface;

    /**
     * The date and time that TrueLayer determined that the payment was ready to be credited.
     *
     * @return \DateTimeInterface|null
     */
    public function getCreditableAt(): ?\DateTimeInterface;

    /**
     * Information about the authorization flow the payment went through. Null if the payment transitioned
     * immediately to authorized or failed.
     *
     * @return ConfigurationInterface|null
     */
    public function getAuthorizationFlowConfig(): ?ConfigurationInterface;

    /**
     * Starts building a refund request for this payment.
     *
     * @throws InvalidArgumentException
     *
     * @return RefundRequestInterface
     */
    public function refund(): RefundRequestInterface;

    /**
     * Retrieves a single refund created from this payment.
     *
     * @param string $refundId
     *
     * @throws ApiResponseUnsuccessfulException
     * @throws InvalidArgumentException
     * @throws SignerException
     * @throws ApiRequestJsonSerializationException
     *
     * @return RefundRetrievedInterface
     */
    public function getRefund(string $refundId): RefundRetrievedInterface;

    /**
     * Retrieves all refunds created from this payment.
     *
     * @throws ApiResponseUnsuccessfulException
     * @throws InvalidArgumentException
     * @throws SignerException
     * @throws ApiRequestJsonSerializationException
     *
     * @return RefundRetrievedInterface[]
     */
    public function getRefunds(): array;
}
