<?php

declare(strict_types=1);

namespace TrueLayer\Entities\SignupPlus;

use TrueLayer\Entities\EntityBuilder;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Interfaces\SignupPlus\SignupPlusAuthUriRequestInterface;
use TrueLayer\Interfaces\SignupPlus\SignupPlusBuilderInterface;

final class SignupPlusBuilder extends EntityBuilder implements SignupPlusBuilderInterface
{
    /**
     * @throws InvalidArgumentException
     *
     * @return SignupPlusAuthUriRequestInterface
     */
    public function authUri(): SignupPlusAuthUriRequestInterface
    {
        return $this->entityFactory->make(SignupPlusAuthUriRequestInterface::class);
    }
}
