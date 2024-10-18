<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payment\AuthorizationFlow;

use TrueLayer\Attributes\Field;
use TrueLayer\Entities\Entity;
use TrueLayer\Interfaces\Payment\AuthorizationFlow\ConfigurationInterface;

class Configuration extends Entity implements ConfigurationInterface
{
    /**
     * @var string
     */
    #[Field('redirect.return_uri')]
    protected string $redirectReturnUri;

    /**
     * @return string|null
     */
    public function getRedirectReturnUri(): ?string
    {
        return $this->redirectReturnUri ?? null;
    }
}
