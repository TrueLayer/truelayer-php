<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\PaymentsProvider;

use TrueLayer\Interfaces\ArrayableInterface;
use TrueLayer\Interfaces\HasAttributesInterface;

interface SearchProvidersRequestInterface extends ArrayableInterface, HasAttributesInterface
{
    /**
     * @param AuthorizationFlowConfigurationInterface $configuration
     *
     * @return $this
     */
    public function authorizationFlowConfiguration(AuthorizationFlowConfigurationInterface $configuration): self;

    /**
     * @param SearchCapabilitiesInterface $capabilities
     *
     * @return $this
     */
    public function capabilities(SearchCapabilitiesInterface $capabilities): self;

    /**
     * @param string[] $countries
     *
     * @return $this
     */
    public function countries(array $countries): self;

    /**
     * @param string[] $currencies
     *
     * @return $this
     */
    public function currencies(array $currencies): self;

    /**
     * @param string[] $customerSegments
     *
     * @return $this
     */
    public function customerSegments(array $customerSegments): self;

    /**
     * @param string $releaseChannel
     *
     * @return $this
     */
    public function releaseChannel(string $releaseChannel): self;
}
