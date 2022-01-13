<?php

declare(strict_types=1);

namespace TrueLayer\Models\Payment\PaymentRetrieved\AuthorizationFlow\Action;

use TrueLayer\Constants\AuthorizationFlowActionTypes;
use TrueLayer\Contracts\Payment\AuthorizationFlow\Action\WaitActionInterface;
use TrueLayer\Models\Payment\PaymentRetrieved\AuthorizationFlow\Action;

class WaitAction extends Action implements WaitActionInterface
{
    /**
     * @var string[]
     */
    protected array $arrayFields = [
        'type'
    ];

    /**
     * @return string
     */
    public function getType(): string
    {
        return AuthorizationFlowActionTypes::WAIT;
    }
}

