<?php

declare(strict_types=1);

namespace TrueLayer\Models\Payment;

use DateTime;
use Exception;
use TrueLayer\Constants\Currencies;
use TrueLayer\Constants\PaymentStatus;
use TrueLayer\Contracts\Beneficiary\BeneficiaryInterface;
use TrueLayer\Contracts\Payment\PaymentMethodInterface;
use TrueLayer\Contracts\Payment\PaymentRetrievedInterface;
use TrueLayer\Contracts\UserInterface;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Exceptions\ValidationException;
use TrueLayer\Models\Model;
use TrueLayer\Validation\AllowedConstant;
use TrueLayer\Validation\ValidType;

final class PaymentRetrieved extends Model implements PaymentRetrievedInterface
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
     * @var DateTime
     */
    protected DateTime $createdAt;

    /**
     * @var string[]
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
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
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

        if (isset($data['payment_method']) && \is_array($data['payment_method'])) {
            $data['payment_method'] = $this->getSdk()->paymentMethod()->fill($data['payment_method']);
        }

        if (isset($data['created_at']) && \is_string($data['created_at'])) {
            $data['created_at'] = new DateTime($data['created_at']);
        }

        return parent::fill($data);
    }
}
