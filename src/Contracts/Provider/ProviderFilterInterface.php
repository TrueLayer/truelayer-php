<?php

declare(strict_types=1);

namespace TrueLayer\Contracts\Provider;

use TrueLayer\Contracts\ArrayableInterface;
use TrueLayer\Contracts\HasAttributesInterface;

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
}
