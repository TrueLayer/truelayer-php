<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\PaymentsProvider;

interface SearchProvidersRequestBuilderInterface
{
    /**
     * @return SearchProvidersRequestInterface
     */
    public function create(): SearchProvidersRequestInterface;

    /**
     * @return AuthorizationFlowConfigurationInterface
     */
    public function authorizationFlowConfiguration(): AuthorizationFlowConfigurationInterface;

    /**
     * @return SearchCapabilitiesInterface
     */
    public function capabilities(): SearchCapabilitiesInterface;
}
