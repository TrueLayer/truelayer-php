<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payment\PaymentRetrieved\AuthorizationFlow\Action;

use TrueLayer\Constants\AuthorizationFlowActionTypes;
use TrueLayer\Interfaces\Payment\AuthorizationFlow\Action\WaitActionInterface;
use TrueLayer\Entities\Payment\PaymentRetrieved\AuthorizationFlow\Action;

class WaitAction extends Action implements WaitActionInterface
{
    /**
     * @var string[]
     */
    protected array $arrayFields = [
        'type',
    ];

    /**
     * @return string
     */
    public function getType(): string
    {
        return AuthorizationFlowActionTypes::WAIT;
    }
}
