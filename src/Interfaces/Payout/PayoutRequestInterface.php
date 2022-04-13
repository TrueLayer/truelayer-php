<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Payout;

use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Exceptions\SignerException;
use TrueLayer\Exceptions\ValidationException;
use TrueLayer\Interfaces\HasAttributesInterface;

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
     * @param PayoutBeneficiaryInterface $beneficiary
     *
     * @return PayoutRequestInterface
     */
    public function beneficiary(PayoutBeneficiaryInterface $beneficiary): PayoutRequestInterface;

    /**
     * @throws InvalidArgumentException
     * @throws ValidationException
     * @throws SignerException
     * @throws ApiRequestJsonSerializationException
     * @throws ApiResponseUnsuccessfulException
     *
     * @return PayoutCreatedInterface
     */
    public function create(): PayoutCreatedInterface;
}
