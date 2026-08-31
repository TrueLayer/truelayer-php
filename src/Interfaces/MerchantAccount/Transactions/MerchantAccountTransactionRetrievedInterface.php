<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\MerchantAccount\Transactions;

interface MerchantAccountTransactionRetrievedInterface
{
    /**
     * @return string
     */
    public function getType(): string;

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
    public function getStatus(): string;
}
