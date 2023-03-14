<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Payment;

use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Exceptions\SignerException;
use TrueLayer\Exceptions\ValidationException;
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
     * @return ConfigurationInterface|null
     */
    public function getAuthorizationFlowConfig(): ?ConfigurationInterface;

    /**
     * @throws InvalidArgumentException
     * @throws ValidationException
     *
     * @return RefundRequestInterface
     */
    public function refund(): RefundRequestInterface;

    /**
     * @param string $refundId
     *
     * @throws ApiRequestJsonSerializationException
     * @throws ApiResponseUnsuccessfulException
     * @throws InvalidArgumentException
     * @throws SignerException
     * @throws ValidationException
     *
     * @return RefundRetrievedInterface
     */
    public function getRefund(string $refundId): RefundRetrievedInterface;

    /**
     * @throws ApiRequestJsonSerializationException
     * @throws ApiResponseUnsuccessfulException
     * @throws InvalidArgumentException
     * @throws SignerException
     * @throws ValidationException
     *
     * @return RefundRetrievedInterface[]
     */
    public function getRefunds(): array;
}
