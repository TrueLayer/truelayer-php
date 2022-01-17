<?php

declare(strict_types=1);

namespace TrueLayer\Contracts\Payment\AuthorizationFlow\Action;

use TrueLayer\Contracts\ArrayableInterface;

interface ActionInterface extends ArrayableInterface
{
    /**
     * @return string
     */
    public function getType(): string;
}
