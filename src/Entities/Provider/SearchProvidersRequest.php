<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Provider;

use TrueLayer\Entities\Entity;
use TrueLayer\Interfaces\Provider\SearchProvidersRequestInterface;

final class SearchProvidersRequest extends Entity implements SearchProvidersRequestInterface
{
    /**
     * @var array<string, mixed>|null
     */
    protected ?array $authorizationFlow = null;

    /**
     * @var array<string, mixed>|null
     */
    protected ?array $capabilities = null;

    /**
     * @var string[]|null
     */
    protected ?array $countries = null;

    /**
     * @var string[]|null
     */
    protected ?array $currencies = null;

    /**
     * @var string[]|null
     */
    protected ?array $customerSegments = null;

    /**
     * @var string|null
     */
    protected ?string $releaseChannel = null;

    /**
     * @var array|string[]
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
     * @return array<string, mixed>|null
     */
    public function getAuthorizationFlow(): ?array
    {
        return $this->authorizationFlow;
    }

    /**
     * @param array<string, mixed> $authorizationFlow
     * @return $this
     */
    public function authorizationFlow(array $authorizationFlow): self
    {
        $this->authorizationFlow = $authorizationFlow;
        return $this;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getCapabilities(): ?array
    {
        return $this->capabilities;
    }

    /**
     * @param array<string, mixed> $capabilities
     * @return $this
     */
    public function capabilities(array $capabilities): self
    {
        $this->capabilities = $capabilities;
        return $this;
    }

    /**
     * @return string[]|null
     */
    public function getCountries(): ?array
    {
        return $this->countries;
    }

    /**
     * @param string[] $countries
     * @return $this
     */
    public function countries(array $countries): self
    {
        $this->countries = $countries;
        return $this;
    }

    /**
     * @return string[]|null
     */
    public function getCurrencies(): ?array
    {
        return $this->currencies;
    }

    /**
     * @param string[] $currencies
     * @return $this
     */
    public function currencies(array $currencies): self
    {
        $this->currencies = $currencies;
        return $this;
    }

    /**
     * @return string[]|null
     */
    public function getCustomerSegments(): ?array
    {
        return $this->customerSegments;
    }

    /**
     * @param string[] $customerSegments
     * @return $this
     */
    public function customerSegments(array $customerSegments): self
    {
        $this->customerSegments = $customerSegments;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getReleaseChannel(): ?string
    {
        return $this->releaseChannel;
    }

    /**
     * @param string $releaseChannel
     * @return $this
     */
    public function releaseChannel(string $releaseChannel): self
    {
        $this->releaseChannel = $releaseChannel;
        return $this;
    }
}