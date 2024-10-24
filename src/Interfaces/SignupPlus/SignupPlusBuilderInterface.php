<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\SignupPlus;

interface SignupPlusBuilderInterface
{
    /**
     * @return SignupPlusAuthUriRequestInterface
     */
    public function authUri(): SignupPlusAuthUriRequestInterface;

    /**
     * @return SignupPlusUserDataRequestInterface
     */
    public function userData(): SignupPlusUserDataRequestInterface;
}
