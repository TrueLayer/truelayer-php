<?php

declare(strict_types=1);

namespace TrueLayer\Services\Payment;

use TrueLayer\Constants\Currencies;
use TrueLayer\Constants\PaymentMethods;
use TrueLayer\Contracts\Beneficiary\BeneficiaryInterface;
use TrueLayer\Contracts\Payment\PaymentCreatedInterface;
use TrueLayer\Contracts\Payment\PaymentRequestInterface;
use TrueLayer\Contracts\UserInterface;
use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\ValidationException;
use TrueLayer\Traits\HasAttributes;
use TrueLayer\Traits\WithSdk;
use TrueLayer\Validation\AllowedConstant;
use TrueLayer\Validation\ValidType;

final class PaymentRequest implements PaymentRequestInterface
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
        return $this->set('beneficiary', $beneficiary);
    }

    /**
     * @param UserInterface $user
     *
     * @return PaymentRequestInterface
     */
    public function user(UserInterface $user): PaymentRequestInterface
    {
        return $this->set('user', $user);
    }

    /**
     * @return PaymentCreatedInterface
     * @throws ApiRequestJsonSerializationException
     * @throws ApiResponseUnsuccessfulException
     * @throws ValidationException
     */
    public function create(): PaymentCreatedInterface
    {
        return PaymentApi::make($this->getSdk())->create($this);
    }

    /**
     * @return mixed[]
     */
    private function rules(): array
    {
        return [
            'amount_in_minor' => 'required|int|min:1',
            'currency' => ['required', 'string', AllowedConstant::in(Currencies::class)],
            'payment_method.type' => ['required', 'string', AllowedConstant::in(PaymentMethods::class)],
            'payment_method.statement_reference' => 'required|string',
            'user' => ['required', ValidType::of(UserInterface::class)],
            'beneficiary' => ['required', ValidType::of(BeneficiaryInterface::class)],
        ];
    }
}
