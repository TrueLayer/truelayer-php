<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Scheme;

interface InstantSchemeSelectionInterface
{
    /**
     * Set whether to allow providers that might charge the remitter a transaction fee.
     *
     * If false, only providers supporting schemes that are free will be available to select in the provider selection action.
     * Unless explicitly set, will default to false.
     *
     * @param bool $allow
     * @return InstantSchemeSelectionInterface
     */
    public function allowRemitterFee(bool $allow): InstantSchemeSelectionInterface;

    /**
     * Whether to allow providers that might charge the remitter a transaction fee.
     *
     * If false, only providers supporting schemes that are free will be available to select in the provider selection action.
     * Unless explicitly set, will default to false.
     *
     * @return bool
     */
    public function getAllowRemitterFee(): bool;

    /**
     * The type of scheme selection.
     *
     * @return string
     */
    public function getType(): string;
}
