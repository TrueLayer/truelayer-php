<?php

declare(strict_types=1);

namespace TrueLayer\Models\Payment;

use Exception;
use Illuminate\Support\Carbon;
use TrueLayer\Constants\Currencies;
use TrueLayer\Constants\PaymentStatus;
use TrueLayer\Contracts\Beneficiary\BeneficiaryInterface;
use TrueLayer\Contracts\Payment\PaymentMethodInterface;
use TrueLayer\Contracts\Payment\PaymentRetrievedInterface;
use TrueLayer\Contracts\UserInterface;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Exceptions\ValidationException;
use TrueLayer\Models\Model;
use TrueLayer\Services\Util\Type;
use TrueLayer\Validation\AllowedConstant;
use TrueLayer\Validation\ValidType;

class PaymentRetrieved extends Model implements PaymentRetrievedInterface
{
    /**
     * @var string
     */
    protected string $id;

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
     * @var string
     */
    protected string $status;

    /**
     * @var PaymentMethodInterface
     */
    protected PaymentMethodInterface $paymentMethod;

    /**
     * @var BeneficiaryInterface
     */
    protected BeneficiaryInterface $beneficiary;

    /**
     * @var UserInterface
     */
    protected UserInterface $user;

    /**
     * @var Carbon
     */
    protected Carbon $createdAt;

    /**
     * @return string[]
     */
    protected array $arrayFields = [
        'id',
        'status',
        'created_at',
        'amount_in_minor',
        'currency',
        'user',
        'beneficiary',
        'payment_method',
    ];

    /**
     * @return mixed[]
     */
    protected function rules(): array
    {
        return [
            'id' => 'required|string',
            'status' => 'required|string',
            'created_at' => 'required|date',
            'amount_in_minor' => 'required|int|min:1',
            'currency' => ['required', 'string', AllowedConstant::in(Currencies::class)],
            'payment_method' => ['required', ValidType::of(PaymentMethodInterface::class)],
            'user' => ['required', ValidType::of(UserInterface::class)],
            'beneficiary' => ['required', ValidType::of(BeneficiaryInterface::class)],
        ];
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getAmountInMinor(): int
    {
        return $this->amountInMinor;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @return PaymentMethodInterface
     */
    public function getPaymentMethod(): PaymentMethodInterface
    {
        return $this->paymentMethod;
    }

    /**
     * @return BeneficiaryInterface
     */
    public function getBeneficiary(): BeneficiaryInterface
    {
        return $this->beneficiary;
    }

    /**
     * @return UserInterface
     */
    public function getUser(): UserInterface
    {
        return $this->user;
    }

    /**
     * @throws Exception
     *
     * @return Carbon
     */
    public function getCreatedAt(): Carbon
    {
        return $this->createdAt;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @return bool
     */
    public function isAuthorizationRequired(): bool
    {
        return $this->getStatus() === PaymentStatus::AUTHORIZATION_REQUIRED;
    }

    /**
     * @return bool
     */
    public function isAuthorizing(): bool
    {
        return $this->getStatus() === PaymentStatus::AUTHORIZING;
    }

    /**
     * @return bool
     */
    public function isAuthorized(): bool
    {
        return $this->getStatus() === PaymentStatus::AUTHORIZED;
    }

    /**
     * @return bool
     */
    public function isExecuted(): bool
    {
        return $this->getStatus() === PaymentStatus::EXECUTED;
    }

    /**
     * @return bool
     */
    public function isFailed(): bool
    {
        return $this->getStatus() === PaymentStatus::FAILED;
    }

    /**
     * @return bool
     */
    public function isSettled(): bool
    {
        return $this->getStatus() === PaymentStatus::SETTLED;
    }

    /**
     * @param mixed[] $data
     *
     * @throws ValidationException
     * @throws InvalidArgumentException
     *
     * @return $this
     */
    public function fill(array $data): self
    {
        $sdk = $this->getSdk();

        if ($beneficiaryData = Type::getNullableArray($data, 'beneficiary')) {
            $data['beneficiary'] = $sdk->beneficiary()->fill($beneficiaryData);
        }

        if ($userData = Type::getNullableArray($data, 'user')) {
            $data['user'] = $sdk->user()->fill($userData);
        }

        if ($paymentMethod = Type::getNullableArray($data, 'payment_method')) {
            $data['payment_method'] = $sdk->paymentMethod()->fill($paymentMethod);
        }

        if ($createdAt = Type::getNullableDate($data, 'created_at')) {
            $data['created_at'] = $createdAt;
        }

        return parent::fill($data);
    }
}
