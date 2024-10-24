<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\SignupPlus;

use TrueLayer\Interfaces\HasAttributesInterface;

interface SignupPlusAuthUriRequestInterface extends HasAttributesInterface
{
    /**
     * @param string $paymentId
     *
     * @return SignupPlusAuthUriRequestInterface
     */
    public function paymentId(string $paymentId): SignupPlusAuthUriRequestInterface;

    /**
     * @param string $state
     *
     * @return SignupPlusAuthUriRequestInterface
     */
    public function state(string $state): SignupPlusAuthUriRequestInterface;

    /**
     * @return SignupPlusAuthUriCreatedInterface
     */
    public function create(): SignupPlusAuthUriCreatedInterface;
}
