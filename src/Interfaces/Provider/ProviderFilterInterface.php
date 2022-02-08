<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Provider;

use TrueLayer\Interfaces\ArrayableInterface;
use TrueLayer\Interfaces\HasAttributesInterface;

interface ProviderFilterInterface extends ArrayableInterface, HasAttributesInterface
{
    /**
     * @param string[] $countries
     *
     * @return ProviderFilterInterface
     */
    public function countries(array $countries): ProviderFilterInterface;

    /**
     * @param string $releaseChannel
     *
     * @return ProviderFilterInterface
     */
    public function releaseChannel(string $releaseChannel): ProviderFilterInterface;

    /**
     * @param string[] $customerSegments
     *
     * @return ProviderFilterInterface
     */
    public function customerSegments(array $customerSegments): ProviderFilterInterface;

    /**
     * @param string[] $providerIds
     *
     * @return ProviderFilterInterface
     */
    public function providerIds(array $providerIds): ProviderFilterInterface;

    /**
     * @param string[] $providerIds
     *
     * @return ProviderFilterInterface
     */
    public function excludesProviderIds(array $providerIds): ProviderFilterInterface;
}
