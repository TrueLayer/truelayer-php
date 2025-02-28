<?php

declare(strict_types=1);

namespace TrueLayer\Entities\SignupPlus;

use TrueLayer\Entities\EntityBuilder;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Interfaces\SignupPlus\SignupPlusAuthUriRequestInterface;
use TrueLayer\Interfaces\SignupPlus\SignupPlusBuilderInterface;
use TrueLayer\Interfaces\SignupPlus\SignupPlusUserDataRequestInterface;

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

    /**
     * @throws InvalidArgumentException
     *
     * @return SignupPlusUserDataRequestInterface
     */
    public function userData(): SignupPlusUserDataRequestInterface
    {
        return $this->entityFactory->make(SignupPlusUserDataRequestInterface::class);
    }
}
