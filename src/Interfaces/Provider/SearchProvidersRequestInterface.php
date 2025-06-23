<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Provider;

interface SearchProvidersRequestInterface
{
    /**
     * @return array<string, mixed>|null
     */
    public function getAuthorizationFlow(): ?array;

    /**
     * @param array<string, mixed> $authorizationFlow
     * @return $this
     */
    public function authorizationFlow(array $authorizationFlow): self;

    /**
     * @return array<string, mixed>|null
     */
    public function getCapabilities(): ?array;

    /**
     * @param array<string, mixed> $capabilities
     * @return $this
     */
    public function capabilities(array $capabilities): self;

    /**
     * @return string[]|null
     */
    public function getCountries(): ?array;

    /**
     * @param string[] $countries
     * @return $this
     */
    public function countries(array $countries): self;

    /**
     * @return string[]|null
     */
    public function getCurrencies(): ?array;

    /**
     * @param string[] $currencies
     * @return $this
     */
    public function currencies(array $currencies): self;

    /**
     * @return string[]|null
     */
    public function getCustomerSegments(): ?array;

    /**
     * @param string[] $customerSegments
     * @return $this
     */
    public function customerSegments(array $customerSegments): self;

    /**
     * @return string|null
     */
    public function getReleaseChannel(): ?string;

    /**
     * @param string $releaseChannel
     * @return $this
     */
    public function releaseChannel(string $releaseChannel): self;
}