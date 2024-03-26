<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payment\PaymentRetrieved;

use TrueLayer\Entities\Payment\PaymentRetrieved;
use TrueLayer\Interfaces\Payment\AuthorizationFlow\AuthorizationFlowInterface;
use TrueLayer\Interfaces\Payment\AuthorizationFlow\ConfigurationInterface;

class _PaymentWithAuthorizationConfig extends PaymentRetrieved
{
    /**
     * @var AuthorizationFlowInterface
     */
    protected AuthorizationFlowInterface $authorizationFlow;

    /**
     * @return mixed[]
     */
    protected function casts(): array
    {
        return \array_merge(parent::casts(), [
            'authorization_flow' => AuthorizationFlowInterface::class,
        ]);
    }

    /**
     * @return mixed[]
     */
    protected function arrayFields(): array
    {
        return \array_merge(parent::arrayFields(), [
            'authorization_flow',
        ]);
    }

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
        return $this->getAuthorizationFlow()
            ? $this->getAuthorizationFlow()->getConfig()
            : null;
    }
}
