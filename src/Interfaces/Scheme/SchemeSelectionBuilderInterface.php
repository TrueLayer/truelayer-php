<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Scheme;

interface SchemeSelectionBuilderInterface
{
    /**
     * Only allow providers that support instant payments.
     *
     * @return InstantOnlySchemeSelectionInterface
     */
    public function instantOnly(): InstantOnlySchemeSelectionInterface;

    /**
     * Prefer providers that allow instant payments, but allow defaulting back to non-instant payments if unavailable.
     *
     * @return InstantPreferredSchemeSelectionInterface
     */
    public function instantPreferred(): InstantPreferredSchemeSelectionInterface;

    /**
     * Indicates that the scheme is to be selected from a collection.
     *
     * This scheme selection method is only supported by some versions of TrueLayer's mobile SDKs.
     * Only use this option when you're sure that the end user has a version of your app on their device that supports scheme selection, or they won't be able to complete the payment.
     * If you integrate with TrueLayer APIs directly, you must show the user a UI for scheme selection.
     * @return UserSelectedSchemeSelectionInterface
     */
    public function userSelected(): UserSelectedSchemeSelectionInterface;

    /*
     * @return PreselectedSchemeSelectionInterface
     */
    public function preselected(): PreselectedSchemeSelectionInterface;
}
