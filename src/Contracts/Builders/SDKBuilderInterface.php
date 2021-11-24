<?php

namespace TrueLayer\Contracts\Builders;

use TrueLayer\Contracts\Models\ConfigInterface;
use TrueLayer\Contracts\SDKInterface;

interface SDKBuilderInterface extends ConfigInterface
{
    /**
     * @return SDKInterface
     */
    public function authorize(): SDKInterface;
}
