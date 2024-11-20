<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\SignupPlus;

interface SignupPlusUserDataRequestInterface
{
    /**
     * @param string $paymentId
     *
     * @return SignupPlusUserDataRequestInterface
     */
    public function paymentId(string $paymentId): SignupPlusUserDataRequestInterface;

    /**
     * @param string $mandateId
     *
     * @return SignupPlusUserDataRequestInterface
     */
    public function mandateId(string $mandateId): SignupPlusUserDataRequestInterface;

    /**
     * @return SignupPlusUserDataRetrievedInterface
     */
    public function retrieve(): SignupPlusUserDataRetrievedInterface;
}
