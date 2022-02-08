<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Payment\AuthorizationFlow;

use TrueLayer\Interfaces\ArrayableInterface;

interface AuthorizationFlowInterface extends ArrayableInterface
{
    /**
     * @return ConfigurationInterface|null
     */
    public function getConfig(): ?ConfigurationInterface;

    /**
     * @return ActionInterface|null
     */
    public function getNextAction(): ?ActionInterface;
}
