<?php

declare(strict_types=1);

namespace TrueLayer\Contracts\Payment;

use DateTime;
use Exception;
use Illuminate\Support\Carbon;
use TrueLayer\Contracts\ArrayableInterface;
use TrueLayer\Contracts\Beneficiary\BeneficiaryInterface;
use TrueLayer\Contracts\UserInterface;

interface PaymentRetrievedInterface extends ArrayableInterface
{
    /**
     * @return string
     */
    public function getId(): string;

    /**
     * @return int
     */
    public function getAmountInMinor(): int;

    /**
     * @return string
     */
    public function getCurrency(): string;

    /**
     * @return string
     */
    public function getStatementReference(): string;

    /**
     * @return BeneficiaryInterface|null
     */
    public function getBeneficiary(): ?BeneficiaryInterface;

    /**
     * @return UserInterface|null
     */
    public function getUser(): ?UserInterface;

    /**
     * @throws Exception
     *
     * @return Carbon
     */
    public function getCreatedAt(): Carbon;

    /**
     * @return string
     */
    public function getStatus(): string;

    /**
     * @return bool
     */
    public function isAuthorizationRequired(): bool;

    /**
     * @return bool
     */
    public function isAuthorizing(): bool;

    /**
     * @return bool
     */
    public function isAuthorized(): bool;

    /**
     * @return bool
     */
    public function isExecuted(): bool;

    /**
     * @return bool
     */
    public function isFailed(): bool;

    /**
     * @return bool
     */
    public function isSettled(): bool;
}
