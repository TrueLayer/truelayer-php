<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payment\AuthorizationFlow;

use TrueLayer\Entities\Entity;
use TrueLayer\Interfaces\Payment\AuthorizationFlow\ConfigurationInterface;

class Configuration extends Entity implements ConfigurationInterface
{
    /**
     * @var string
     */
    protected string $redirectReturnUri;

    /**
     * @var string[]
     */
    protected array $arrayFields = [
        'redirect.return_uri' => 'redirect_return_uri',
    ];

    /**
     * @var string[]
     */
    protected array $rules = [
        'redirect.return_uri' => 'required_if:redirect.status,supported',
    ];

    /**
     * @return string|null
     */
    public function getRedirectReturnUri(): ?string
    {
        return $this->redirectReturnUri ?? null;
    }
}
