<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Payment;

use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Exceptions\SignerException;
use TrueLayer\Interfaces\Payment\AuthorizationFlow\ConfigurationInterface;

interface PaymentSettledInterface extends PaymentRetrievedInterface
{
    /**
     * @return PaymentSourceInterface
     */
    public function getPaymentSource(): PaymentSourceInterface;

    /**
     * @return \DateTimeInterface
     */
    public function getSettledAt(): \DateTimeInterface;

    /**
     * @return \DateTimeInterface
     */
    public function getExecutedAt(): \DateTimeInterface;

    /**
     * @return \DateTimeInterface|null
     */
    public function getCreditableAt(): ?\DateTimeInterface;

    /**
     * @return ConfigurationInterface|null
     */
    public function getAuthorizationFlowConfig(): ?ConfigurationInterface;

    /**
     * @throws InvalidArgumentException
     *
     * @return RefundRequestInterface
     */
    public function refund(): RefundRequestInterface;

    /**
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
     * @throws ApiResponseUnsuccessfulException
     * @throws InvalidArgumentException
     * @throws SignerException
     * @throws ApiRequestJsonSerializationException
     *
     * @return RefundRetrievedInterface[]
     */
    public function getRefunds(): array;
}
