<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payment\AuthorizationFlow\Action;

use TrueLayer\Attributes\Field;
use TrueLayer\Entities\Payment\AuthorizationFlow\Action;
use TrueLayer\Interfaces\Payment\AuthorizationFlow\Action\RedirectActionInterface;
use TrueLayer\Interfaces\Provider\ProviderInterface;

class RedirectAction extends Action implements RedirectActionInterface
{
    /**
     * @var string
     */
    #[Field]
    protected string $uri;

    /**
     * @var ProviderInterface
     */
    #[Field('metadata')]
    protected ProviderInterface $provider;

    /**
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * @return string
     */
    public function getMetadataType(): string
    {
        return 'provider';
    }

    /**
     * @return ProviderInterface|null
     */
    public function getProvider(): ?ProviderInterface
    {
        return $this->provider ?? null;
    }
}
