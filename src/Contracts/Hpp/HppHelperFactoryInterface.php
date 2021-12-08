<?php

namespace TrueLayer\Contracts\Hpp;

interface HppHelperFactoryInterface
{
    /**
     * @return HppHelperInterface
     */
    public function make(): HppHelperInterface;
}
