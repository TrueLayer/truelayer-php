<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payment\PaymentRetrieved;

use TrueLayer\Attributes\Field;
use TrueLayer\Entities\Payment\PaymentRetrieved;
use TrueLayer\Interfaces\Payment\AuthorizationFlow\AuthorizationFlowInterface;
use TrueLayer\Interfaces\Payment\AuthorizationFlow\ConfigurationInterface;

class _PaymentWithAuthorizationConfig extends PaymentRetrieved
{
    /**
     * @var AuthorizationFlowInterface
     */
    #[Field]
    protected AuthorizationFlowInterface $authorizationFlow;

    /**
     * @return AuthorizationFlowInterface|null
     */
    public function getAuthorizationFlow(): ?AuthorizationFlowInterface
    {
        return $this->authorizationFlow ?? null;
    }

    /**
     * @return ConfigurationInterface|null
     */
    public function getAuthorizationFlowConfig(): ?ConfigurationInterface
    {
        return $this->getAuthorizationFlow()?->getConfig();
    }
}
