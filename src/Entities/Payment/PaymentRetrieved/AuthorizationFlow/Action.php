<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payment\PaymentRetrieved\AuthorizationFlow;

use TrueLayer\Interfaces\Payment\AuthorizationFlow\ActionInterface;
use TrueLayer\Entities\Entity;

abstract class Action extends Entity implements ActionInterface
{
    /**
     * @return string
     */
    abstract public function getType(): string;
}
