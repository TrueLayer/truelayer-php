<?php

declare(strict_types=1);

namespace TrueLayer\Entities\PaymentsProvider;

use TrueLayer\Entities\EntityBuilder;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Interfaces\PaymentsProvider\AuthorizationFlowConfigurationInterface;
use TrueLayer\Interfaces\PaymentsProvider\SearchCapabilitiesInterface;
use TrueLayer\Interfaces\PaymentsProvider\SearchProvidersRequestBuilderInterface;
use TrueLayer\Interfaces\PaymentsProvider\SearchProvidersRequestInterface;

class SearchProvidersRequestBuilder extends EntityBuilder implements SearchProvidersRequestBuilderInterface
{
    /**
     * @throws InvalidArgumentException
     *
     * @return SearchProvidersRequestInterface
     */
    public function create(): SearchProvidersRequestInterface
    {
        return $this->entityFactory->make(SearchProvidersRequestInterface::class);
    }

    /**
     * @throws InvalidArgumentException
     *
     * @return AuthorizationFlowConfigurationInterface
     */
    public function authorizationFlowConfiguration(): AuthorizationFlowConfigurationInterface
    {
        return $this->entityFactory->make(AuthorizationFlowConfigurationInterface::class);
    }

    /**
     * @throws InvalidArgumentException
     *
     * @return SearchCapabilitiesInterface
     */
    public function capabilities(): SearchCapabilitiesInterface
    {
        return $this->entityFactory->make(SearchCapabilitiesInterface::class);
    }
}
