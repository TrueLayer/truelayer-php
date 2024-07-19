<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payment\AuthorizationFlow\Action;

use TrueLayer\Entities\Payment\AuthorizationFlow\Action;
use TrueLayer\Interfaces\Payment\AuthorizationFlow\Action\RedirectActionInterface;
use TrueLayer\Interfaces\Provider\ProviderInterface;

class RedirectAction extends Action implements RedirectActionInterface
{
    /**
     * @var string
     */
    protected string $uri;

    /**
     * @var ProviderInterface
     */
    protected ProviderInterface $provider;

    /**
     * @var array|string[]
     */
    protected array $casts = [
        'metadata' => ProviderInterface::class,
    ];

    /**
     * @var string[]
     */
    protected array $arrayFields = [
        'type',
        'uri',
        'metadata' => 'provider',
    ];

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
