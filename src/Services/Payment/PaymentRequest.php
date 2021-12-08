<?php

declare(strict_types=1);

namespace TrueLayer\Services\Payment;

use TrueLayer\Constants\PaymentMethods;
use TrueLayer\Contracts\Beneficiary\BeneficiaryInterface;
use TrueLayer\Contracts\Payment\PaymentCreatedInterface;
use TrueLayer\Contracts\Payment\PaymentRequestInterface;
use TrueLayer\Contracts\UserInterface;
use TrueLayer\Services\Payment\Api\PaymentCreate;
use TrueLayer\Traits\HasAttributes;
use TrueLayer\Traits\WithSdk;

class PaymentRequest implements PaymentRequestInterface
{
    use WithSdk, HasAttributes;

    /**
     * @param int $amount
     *
     * @return PaymentRequestInterface
     */
    public function amountInMinor(int $amount): PaymentRequestInterface
    {
        return $this->set('amount_in_minor', $amount);
    }

    /**
     * @param string $currency
     *
     * @return PaymentRequestInterface
     */
    public function currency(string $currency): PaymentRequestInterface
    {
        return $this->set('currency', $currency);
    }

    /**
     * @param string $statementReference
     *
     * @return PaymentRequestInterface
     */
    public function statementReference(string $statementReference): PaymentRequestInterface
    {
        $this->set('payment_method.type', PaymentMethods::BANK_TRANSFER);
        $this->set('payment_method.statement_reference', $statementReference);

        return $this;
    }

    /**
     * @param BeneficiaryInterface $beneficiary
     *
     * @return PaymentRequestInterface
     */
    public function beneficiary(BeneficiaryInterface $beneficiary): PaymentRequestInterface
    {
        return $this->set('beneficiary', $beneficiary->toArray());
    }

    /**
     * @param UserInterface $user
     *
     * @return PaymentRequestInterface
     */
    public function user(UserInterface $user): PaymentRequestInterface
    {
        return $this->set('user', $user->toArray());
    }

    /**
     * @throws \TrueLayer\Exceptions\ApiRequestJsonSerializationException
     * @throws \TrueLayer\Exceptions\ApiRequestValidationException
     * @throws \TrueLayer\Exceptions\ApiResponseUnsuccessfulException
     * @throws \TrueLayer\Exceptions\ApiResponseValidationException
     *
     * @return PaymentCreatedInterface
     */
    public function create(): PaymentCreatedInterface
    {
        $sdk = $this->getSdk();
        $data = PaymentCreate::make($sdk)->execute($this->toArray());

        return PaymentCreated::make($sdk)->fill($data);
    }
}
