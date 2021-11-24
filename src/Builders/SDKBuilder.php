<?php

declare(strict_types=1);

namespace TrueLayer\Builders;

use TrueLayer\Contracts\Builders\SDKBuilderInterface;
use TrueLayer\Contracts\SDKInterface;
use TrueLayer\Models\Config;
use TrueLayer\SDK;

class SDKBuilder extends Config implements SDKBuilderInterface
{
    /**
     * @return SDKInterface
     */
    public function authorize(): SDKInterface
    {
        return SDK::make($this);
    }
}
