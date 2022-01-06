<?php

declare(strict_types=1);

namespace TrueLayer\Models\Payment;

use DateTime;
use Exception;
use TrueLayer\Constants\Currencies;
use TrueLayer\Constants\PaymentStatus;
use TrueLayer\Contracts\Beneficiary\BeneficiaryInterface;
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
    private string $id;

    /**
     * @var int
     */
    private int $amountInMinor;

    /**
     * @var string
     */
    private string $currency;

    /**
     * @var string
     */
    private string $statementReference;

    /**
     * @var string
     */
    private string $status;

    /**
     * @var BeneficiaryInterface
     */
    private BeneficiaryInterface $beneficiary;

    /**
     * @var UserInterface
     */
    private UserInterface $user;

    /**
     * @var DateTime
     */
    private DateTime $createdAt;

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
        'payment_method.statement_reference' => 'statement_reference',
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
            'payment_method.statement_reference' => 'required|string',
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
     * @return string
     */
    public function getStatementReference(): string
    {
        return $this->statementReference;
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
     * @return DateTime
     * @throws Exception
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
     * @return $this
     * @throws InvalidArgumentException
     * @throws ValidationException
     */
    public function fill(array $data): self
    {
        if (isset($data['beneficiary']) && is_array($data['beneficiary'])) {
            $data['beneficiary'] = $this->getSdk()->beneficiary()->fill($data['beneficiary']);
        }

        if (isset($data['user']) && is_array($data['user'])) {
            $data['user'] = $this->getSdk()->user()->fill($data['user']);
        }

        if (isset($data['created_at']) && is_string($data['created_at'])) {
            $data['created_at'] = new DateTime($data['created_at']);
        }

        return parent::fill($data);
    }
}
