<?php

declare(strict_types=1);

namespace TrueLayer\Models;

use TrueLayer\Constants\BeneficiaryTypes;
use TrueLayer\Constants\ExternalAccountTypes;
use TrueLayer\Constants\PaymentMethods;
use TrueLayer\Contracts\Models\BeneficiaryInterface;
use TrueLayer\Contracts\Models\PaymentInterface;
use TrueLayer\Contracts\Models\UserInterface;

class Payment implements PaymentInterface
{
    /**
     * @var string|null
     */
    private ?string $id = null;

    /**
     * @var int|null
     */
    private ?int $amount = null;

    /**
     * @var string|null
     */
    private ?string $currency = null;

    /**
     * @var string|null
     */
    private ?string $statementReference = null;

    /**
     * @var BeneficiaryInterface|null
     */
    private ?BeneficiaryInterface $beneficiary = null;

    /**
     * @var UserInterface|null
     */
    private ?UserInterface $user = null;

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param string|null $id
     *
     * @return $this
     */
    public function id(string $id = null): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getAmountInMinor(): ?int
    {
        return $this->amount;
    }

    /**
     * @param int|null $amount
     *
     * @return $this
     */
    public function amountInMinor(int $amount = null): self
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    /**
     * @param string|null $currency
     *
     * @return $this
     */
    public function currency(string $currency = null): self
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getStatementReference(): ?string
    {
        return $this->statementReference;
    }

    /**
     * @param string|null $statementReference
     *
     * @return $this
     */
    public function statementReference(string $statementReference = null): self
    {
        $this->statementReference = $statementReference;

        return $this;
    }

    /**
     * @return BeneficiaryInterface|null
     */
    public function getBeneficiary(): ?BeneficiaryInterface
    {
        return $this->beneficiary;
    }

    /**
     * @param BeneficiaryInterface|null $beneficiary
     *
     * @return $this
     */
    public function beneficiary(BeneficiaryInterface $beneficiary = null): self
    {
        $this->beneficiary = $beneficiary;

        return $this;
    }

    /**
     * @return UserInterface|null
     */
    public function getUser(): ?UserInterface
    {
        return $this->user;
    }

    /**
     * @param UserInterface|null $user
     *
     * @return $this
     */
    public function user(UserInterface $user = null): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'amount_in_minor' => $this->getAmountInMinor(),
            'currency' => $this->getCurrency(),
            'payment_method' => [
                'type' => PaymentMethods::BANK_TRANSFER,
                'statement_reference' => $this->getStatementReference(),
            ],
            'user' => $this->getUser()->toArray() ?? null,
            'beneficiary' => $this->getBeneficiary()->toArray() ?? null,
        ];
    }

    /**
     * @param array $data
     *
     * @return PaymentInterface
     */
    public static function fromArray(array $data): PaymentInterface
    {
        $instance = (new static());

        if (empty($data)) {
            return $instance;
        }

        $beneficiary = null;
        $beneficiaryData = $data['beneficiary'] ?? null;
        $beneficiaryType = $beneficiaryData['type'] ?? null;

        if ($beneficiaryType === BeneficiaryTypes::EXTERNAL_ACCOUNT) {
            $schemeType = $beneficiaryData['scheme_identifier']['type'];

            if ($schemeType === ExternalAccountTypes::SORT_CODE_ACCOUNT_NUMBER) {
                $beneficiary = SortCodeAccountNumber::fromArray($beneficiaryData);
            } elseif ($schemeType === ExternalAccountTypes::IBAN) {
                $beneficiary = IbanAccountBeneficiary::fromArray($beneficiaryData);
            }
        } elseif ($beneficiaryType === BeneficiaryTypes::MERCHANT_ACCOUNT) {
            $beneficiary = MerchantAccountBeneficiary::fromArray($beneficiaryData);
        }

        $user = empty($data['user'])
            ? null
            : User::fromArray($data['user']);

        return $instance
            ->id($data['id'] ?? null)
            ->beneficiary($beneficiary)
            ->user($user)
            ->amountInMinor($data['amount_in_minor'] ?? null)
            ->currency($data['currency'] ?? null)
            ->statementReference($data['payment_method']['statement_reference'] ?? null);
    }
}
