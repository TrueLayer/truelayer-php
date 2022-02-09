<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payment\PaymentRetrieved;

use TrueLayer\Interfaces\Payment\AuthorizationFlow\ActionInterface;
use TrueLayer\Interfaces\Payment\PaymentAuthorizingInterface;

final class PaymentAuthorizing extends _PaymentWithAuthorizationConfig implements PaymentAuthorizingInterface
{
    /**
     * @return ActionInterface|null
     */
    public function getAuthorizationFlowNextAction(): ?ActionInterface
    {
        return $this->getAuthorizationFlow()
            ? $this->getAuthorizationFlow()->getNextAction()
            : null;
    }
}
