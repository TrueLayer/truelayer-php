<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Payment\AuthorizationFlow;

use TrueLayer\Interfaces\ArrayableInterface;

interface AuthorizationFlowResponseInterface extends ArrayableInterface
{
    /**
     * @return ActionInterface|null
     */
    public function getNextAction(): ?ActionInterface;

    /**
     * @return string
     */
    public function getStatus(): string;
}
