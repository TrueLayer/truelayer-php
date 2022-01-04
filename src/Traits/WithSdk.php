<?php

declare(strict_types=1);

namespace TrueLayer\Traits;

use TrueLayer\Contracts\Sdk\SdkInterface;

trait WithSdk
{
    private SdkInterface $sdk;

    /**
     * @param SdkInterface $sdk
     */
    public final function __construct(SdkInterface $sdk)
    {
        $this->sdk = $sdk;
    }

    /**
     * @return SdkInterface
     */
    protected function getSdk(): SdkInterface
    {
        return $this->sdk;
    }

    /**
     * @param SdkInterface $sdk
     *
     * @return static
     */
    public static function make(SdkInterface $sdk): self
    {
        return new static($sdk);
    }
}
