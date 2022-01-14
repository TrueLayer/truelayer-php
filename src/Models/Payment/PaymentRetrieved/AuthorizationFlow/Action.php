<?php

declare(strict_types=1);

namespace TrueLayer\Models\Payment\PaymentRetrieved\AuthorizationFlow;

use TrueLayer\Contracts\Payment\AuthorizationFlow\ActionInterface;
use TrueLayer\Models\Model;

abstract class Action extends Model implements ActionInterface
{
    /**
     * @return string
     */
    abstract public function getType(): string;
}
