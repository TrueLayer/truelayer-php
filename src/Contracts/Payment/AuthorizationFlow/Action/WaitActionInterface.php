<?php

declare(strict_types=1);

namespace TrueLayer\Contracts\Payment\AuthorizationFlow\Action;

use TrueLayer\Contracts\ArrayableInterface;

interface WaitActionInterface extends ArrayableInterface
{
    /**
     * @return string
     */
    public function getType(): string;
}
