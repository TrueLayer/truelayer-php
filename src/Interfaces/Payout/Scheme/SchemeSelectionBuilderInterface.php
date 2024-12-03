<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Payout\Scheme;

interface SchemeSelectionBuilderInterface
{
    /**
     * Automatically select a payment scheme that supports instant payments based on currency and geography.
     * This is optimal when quick settlement is essential.
     *
     * @return InstantOnlySchemeSelectionInterface
     */
    public function instantOnly(): InstantOnlySchemeSelectionInterface;

    /**
     * Automatically select a payment scheme that supports instant payments based on currency and geography,
     * with a fallback to a non-instant scheme if instant payment is unavailable.
     * The {@see \TrueLayer\Entities\Payout\PayoutRetrieved\PayoutExecuted} webhook will specify the actual scheme used.
     * This is optimal when slow settlement is not a concern. This is used by default if no scheme selection is provided with {@see \TrueLayer\Entities\Payout\PayoutRequest::schemeSelection()}.
     *
     * @return InstantPreferredSchemeSelectionInterface
     */
    public function instantPreferred(): InstantPreferredSchemeSelectionInterface;

    /**
     * Select a payment scheme compatible with the currency and geographic region to avoid payout failures after submission.
     * This helps with payouts by selecting the better-performing scheme between two similar options in a region, based on various criteria.
     *
     * @return PreselectedSchemeSelectionInterface
     */
    public function preselected(): PreselectedSchemeSelectionInterface;
}
