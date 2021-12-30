<?php

declare(strict_types=1);

namespace TrueLayer\Contracts\Hpp;

interface HppHelperFactoryInterface
{
    /**
     * @return HppHelperInterface
     */
    public function make(): HppHelperInterface;
}
