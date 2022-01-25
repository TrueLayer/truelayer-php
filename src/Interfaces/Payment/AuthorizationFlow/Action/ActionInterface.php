<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Payment\AuthorizationFlow\Action;

use TrueLayer\Interfaces\ArrayableInterface;

interface ActionInterface extends ArrayableInterface
{
    /**
     * @return string
     */
    public function getType(): string;
}
