<?php

declare(strict_types=1);

namespace TrueLayer\Entities\SignupPlus;

use TrueLayer\Entities\Entity;
use TrueLayer\Interfaces\SignupPlus\SignupPlusAuthUriCreatedInterface;

class SignupPlusAuthUriCreated extends Entity implements SignupPlusAuthUriCreatedInterface
{
    /**
     * @var string
     */
    protected string $authUri;

    /**
     * @var string[]
     */
    protected array $arrayFields = [
        'auth_uri',
    ];

    /**
     * @return string
     */
    public function getAuthUri(): string
    {
        return $this->authUri;
    }
}
