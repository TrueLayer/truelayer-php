<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Provider;

use TrueLayer\Interfaces\ArrayableInterface;
use TrueLayer\Interfaces\HasAttributesInterface;

interface ProviderFilterInterface extends ArrayableInterface, HasAttributesInterface
{
    /**
     * @param string[] $countries
     * Only providers from the specified countries will be returned.
     * @see \TrueLayer\Constants\Countries
     *
     * @return ProviderFilterInterface
     */
    public function countries(array $countries): ProviderFilterInterface;

    /**
     * @param string $releaseChannel
     * The lowest stability release stage of a provider that should be returned.
     * Note that many EUR providers are in public_beta or private_beta.
     * Defaults to general_availability
     * @see \TrueLayer\Constants\ReleaseChannels
     *
     * @return ProviderFilterInterface
     */
    public function releaseChannel(string $releaseChannel): ProviderFilterInterface;

    /**
     * @param string[] $customerSegments
     * The customer segments that providers cater to that should be returned. By default, returns retail only.
     * @see \TrueLayer\Constants\CustomerSegments
     *
     * @return ProviderFilterInterface
     */
    public function customerSegments(array $customerSegments): ProviderFilterInterface;

    /**
     * @param string[] $providerIds
     * IDs of providers to include in those returned.
     * e.g. mock-payments-gb-redirect
     *
     * @return ProviderFilterInterface
     */
    public function providerIds(array $providerIds): ProviderFilterInterface;

    /**
     * @param string[] $providerIds
     * IDs of providers to explicitly exclude from those returned.
     * e.g. ob-exclude-this-bank
     *
     * @return ProviderFilterInterface
     */
    public function excludesProviderIds(array $providerIds): ProviderFilterInterface;
}
