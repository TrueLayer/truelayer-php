<?php

declare(strict_types=1);

namespace TrueLayer\Contracts\Payment\AuthorizationFlow\Action;

use TrueLayer\Contracts\ArrayableInterface;
use TrueLayer\Contracts\ProviderInterface;

interface RedirectActionInterface extends ArrayableInterface
{
    /**
     * @return string
     */
    public function getUri(): string;

    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @return string
     */
    public function getMetadataType(): string;

    /**
     * @return ProviderInterface|null
     */
    public function getProvider(): ?ProviderInterface;
}
