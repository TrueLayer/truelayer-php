<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\SignupPlus;

interface SignupPlusBuilderInterface
{
    public function authUri(): SignupPlusAuthUriRequestInterface;
}
