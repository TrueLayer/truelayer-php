<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payment\PaymentRetrieved\AuthorizationFlow;

use TrueLayer\Entities\Entity;
use TrueLayer\Interfaces\Payment\AuthorizationFlow\ActionInterface;

abstract class Action extends Entity implements ActionInterface
{
    /**
     * @return string
     */
    abstract public function getType(): string;
}
