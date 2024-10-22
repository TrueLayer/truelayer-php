<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Payout;

use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Exceptions\SignerException;
use TrueLayer\Interfaces\HasAttributesInterface;
use TrueLayer\Interfaces\Payout\Beneficiary\BeneficiaryInterface;
use TrueLayer\Interfaces\RequestOptionsInterface;

interface PayoutRequestInterface extends HasAttributesInterface
{
    /**
     * @param string $merchantAccountId
     *
     * @return PayoutRequestInterface
     */
    public function merchantAccountId(string $merchantAccountId): PayoutRequestInterface;

    /**
     * @param int $amount
     *
     * @return PayoutRequestInterface
     */
    public function amountInMinor(int $amount): PayoutRequestInterface;

    /**
     * @param string $currency
     *
     * @return PayoutRequestInterface
     */
    public function currency(string $currency): PayoutRequestInterface;

    /**
     * @param BeneficiaryInterface $beneficiary
     *
     * @return PayoutRequestInterface
     */
    public function beneficiary(BeneficiaryInterface $beneficiary): PayoutRequestInterface;

    /**
     * @param array<string, string> $metadata
     *
     * @return PayoutRequestInterface
     */
    public function metadata(array $metadata): PayoutRequestInterface;

    /**
     * @param RequestOptionsInterface $requestOptions
     *
     * @return PayoutRequestInterface
     */
    public function requestOptions(RequestOptionsInterface $requestOptions): PayoutRequestInterface;

    /**
     * @throws ApiRequestJsonSerializationException
     * @throws ApiResponseUnsuccessfulException
     * @throws InvalidArgumentException
     * @throws SignerException
     *
     * @return PayoutCreatedInterface
     */
    public function create(): PayoutCreatedInterface;
}
