<?php

declare(strict_types=1);

namespace TrueLayer\Entities\PaymentsProvider;

use TrueLayer\Entities\Entity;
use TrueLayer\Interfaces\PaymentsProvider\AuthorizationFlowConfigurationInterface;
use TrueLayer\Interfaces\PaymentsProvider\SearchCapabilitiesInterface;
use TrueLayer\Interfaces\PaymentsProvider\SearchProvidersRequestInterface;

class SearchProvidersRequest extends Entity implements SearchProvidersRequestInterface
{
    /**
     * @var array<string, mixed>
     */
    protected array $authorizationFlow;

    /**
     * @var SearchCapabilitiesInterface
     */
    protected SearchCapabilitiesInterface $capabilities;

    /**
     * @var string[]
     */
    protected array $countries;

    /**
     * @var string[]
     */
    protected array $currencies;

    /**
     * @var string[]
     */
    protected array $customerSegments;

    /**
     * @var string
     */
    protected string $releaseChannel;

    /**
     * @var array<string, mixed>
     */
    protected array $casts = [
        'capabilities' => SearchCapabilitiesInterface::class,
    ];

    /**
     * @var array<string>
     */
    protected array $arrayFields = [
        'authorization_flow',
        'capabilities',
        'countries',
        'currencies',
        'customer_segments',
        'release_channel',
    ];

    /**
     * @param AuthorizationFlowConfigurationInterface $configuration
     *
     * @return $this
     */
    public function authorizationFlowConfiguration(AuthorizationFlowConfigurationInterface $configuration): self
    {
        $this->authorizationFlow = [
            'configuration' => $configuration->toArray(),
        ];

        return $this;
    }

    /**
     * @param SearchCapabilitiesInterface $capabilities
     *
     * @return $this
     */
    public function capabilities(SearchCapabilitiesInterface $capabilities): self
    {
        $this->capabilities = $capabilities;

        return $this;
    }

    /**
     * @param string[] $countries
     *
     * @return $this
     */
    public function countries(array $countries): self
    {
        $this->countries = $countries;

        return $this;
    }

    /**
     * @param string[] $currencies
     *
     * @return $this
     */
    public function currencies(array $currencies): self
    {
        $this->currencies = $currencies;

        return $this;
    }

    /**
     * @param string[] $customerSegments
     *
     * @return $this
     */
    public function customerSegments(array $customerSegments): self
    {
        $this->customerSegments = $customerSegments;

        return $this;
    }

    /**
     * @param string $releaseChannel
     *
     * @return $this
     */
    public function releaseChannel(string $releaseChannel): self
    {
        $this->releaseChannel = $releaseChannel;

        return $this;
    }
}
