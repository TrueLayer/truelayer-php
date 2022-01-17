<?php

declare(strict_types=1);

namespace TrueLayer\Contracts\Payment\AuthorizationFlow\Action;

use TrueLayer\Contracts\ProviderInterface;

interface RedirectActionInterface extends ActionInterface
{
    /**
     * @return string
     */
    public function getUri(): string;

    /**
     * @return string
     */
    public function getMetadataType(): string;

    /**
     * @return ProviderInterface|null
     */
    public function getProvider(): ?ProviderInterface;
}
