<?php

declare(strict_types=1);

namespace TrueLayer\Models\Payment;

use Illuminate\Support\Arr;
use TrueLayer\Constants\Currencies;
use TrueLayer\Constants\PaymentMethods;
use TrueLayer\Contracts\Beneficiary\BeneficiaryInterface;
use TrueLayer\Contracts\Payment\PaymentCreatedInterface;
use TrueLayer\Contracts\Payment\PaymentRequestInterface;
use TrueLayer\Contracts\UserInterface;
use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Exceptions\ValidationException;
use TrueLayer\Models\Model;
use TrueLayer\Services\Api\PaymentApi;
use TrueLayer\Validation\AllowedConstant;
use TrueLayer\Validation\ValidType;

final class PaymentRequest extends Model implements PaymentRequestInterface
{
    /**
     * @var int
     */
    protected int $amountInMinor;

    /**
     * @var string
     */
    protected string $currency;

    /**
     * @var string
     */
    protected string $statementReference;

    /**
     * @var BeneficiaryInterface
     */
    protected BeneficiaryInterface $beneficiary;

    /**
     * @var UserInterface
     */
    protected UserInterface $user;

    /**
     * @var string[]
     */
    protected array $arrayFields = [
        'amount_in_minor',
        'currency',
        'user',
        'beneficiary',
        'payment_method.statement_reference' => 'statement_reference',
    ];

    /**
     * @return mixed[]
     */
    protected function rules(): array
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

    /**
     * @param int $amount
     *
     * @return PaymentRequestInterface
     */
    public function amountInMinor(int $amount): PaymentRequestInterface
    {
        $this->amountInMinor = $amount;

        return $this;
    }

    /**
     * @param string $currency
     *
     * @return PaymentRequestInterface
     */
    public function currency(string $currency): PaymentRequestInterface
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * @param string $statementReference
     *
     * @return PaymentRequestInterface
     */
    public function statementReference(string $statementReference): PaymentRequestInterface
    {
        $this->statementReference = $statementReference;

        return $this;
    }

    /**
     * @param BeneficiaryInterface $beneficiary
     *
     * @return PaymentRequestInterface
     */
    public function beneficiary(BeneficiaryInterface $beneficiary): PaymentRequestInterface
    {
        $this->beneficiary = $beneficiary;

        return $this;
    }

    /**
     * @param UserInterface $user
     *
     * @return PaymentRequestInterface
     */
    public function user(UserInterface $user): PaymentRequestInterface
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return mixed[]
     */
    public function all(): array
    {
        $data = parent::all();
        Arr::set($data, 'payment_method.type', PaymentMethods::BANK_TRANSFER);

        return $data;
    }

    /**
     * @throws ApiRequestJsonSerializationException
     * @throws ApiResponseUnsuccessfulException
     * @throws ValidationException
     *
     * @return PaymentCreatedInterface
     */
    public function create(): PaymentCreatedInterface
    {
        return PaymentApi::make($this->getSdk())->create($this);
    }

    /**
     * @param mixed[] $data
     *
     * @throws InvalidArgumentException
     * @throws ValidationException
     *
     * @return $this
     */
    public function fill(array $data): self
    {
        if (isset($data['beneficiary']) && \is_array($data['beneficiary'])) {
            $data['beneficiary'] = $this->getSdk()->beneficiary()->fill($data['beneficiary']);
        }

        if (isset($data['user']) && \is_array($data['user'])) {
            $data['user'] = $this->getSdk()->user()->fill($data['user']);
        }

        return parent::fill($data);
    }
}