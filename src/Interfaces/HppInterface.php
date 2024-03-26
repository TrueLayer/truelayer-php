<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces;

interface HppInterface extends ArrayableInterface
{
    /**
     * @param string $baseUrl
     *
     * @return HppInterface
     */
    public function baseUrl(string $baseUrl): HppInterface;

    /**
     * @return string|null
     */
    public function getBaseUrl(): ?string;

    /**
     * @param string $paymentId
     *
     * @return HppInterface
     */
    public function paymentId(string $paymentId): HppInterface;

    /**
     * @return string|null
     */
    public function getPaymentId(): ?string;

    /**
     * @param string $resourceToken
     *
     * @return HppInterface
     */
    public function resourceToken(string $resourceToken): HppInterface;

    /**
     * @return string|null
     */
    public function getResourceToken(): ?string;

    /**
     * @param string $returnUri
     *
     * @return HppInterface
     */
    public function returnUri(string $returnUri): HppInterface;

    /**
     * @return string|null
     */
    public function getReturnUri(): ?string;

    /**
     * @param string $hex
     *
     * @return HppInterface
     */
    public function primaryColour(string $hex): HppInterface;

    /**
     * @return string|null
     */
    public function getPrimaryColour(): ?string;

    /**
     * @param string $hex
     *
     * @return HppInterface
     */
    public function secondaryColour(string $hex): HppInterface;

    /**
     * @return string|null
     */
    public function getSecondaryColour(): ?string;

    /**
     * @param string $hex
     *
     * @return HppInterface
     */
    public function tertiaryColour(string $hex): HppInterface;

    /**
     * @return string|null
     */
    public function getTertiaryColour(): ?string;

    /**
     * @return string
     */
    public function toUrl(): string;

    /**
     * @return string
     */
    public function __toString(): string;
}
