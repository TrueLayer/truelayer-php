<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Provider;

use TrueLayer\Interfaces\Remitter\RemitterInterface;

interface PreselectedProviderSelectionInterface extends ProviderSelectionInterface
{
    /**
     * @return string|null
     */
    public function getProviderId(): ?string;

    /**
     * @param string $providerId
     * The provider Id the PSU will use for this payment.
     *
     * @return PreselectedProviderSelectionInterface
     */
    public function providerId(string $providerId): PreselectedProviderSelectionInterface;

    /**
     * @return string|null
     */
    public function getDataAccessToken(): ?string;

    /**
     * @param string $dataAccessToken
     *
     * @return PreselectedProviderSelectionInterface
     */
    public function dataAccessToken(string $dataAccessToken): PreselectedProviderSelectionInterface;

    /**
     * @return RemitterInterface|null
     */
    public function getRemitter(): ?RemitterInterface;

    /**
     * @param RemitterInterface $remitter
     *
     * @return PreselectedProviderSelectionInterface
     */
    public function remitter(RemitterInterface $remitter): PreselectedProviderSelectionInterface;
}
