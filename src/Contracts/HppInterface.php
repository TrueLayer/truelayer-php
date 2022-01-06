<?php

declare(strict_types=1);

namespace TrueLayer\Contracts;

use TrueLayer\Exceptions\ValidationException;

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
     * @param string $paymentToken
     *
     * @return HppInterface
     */
    public function paymentToken(string $paymentToken): HppInterface;

    /**
     * @return string|null
     */
    public function getPaymentToken(): ?string;

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
     * @throws ValidationException
     *
     * @return string
     */
    public function toUrl(): string;

    /**
     * @throws ValidationException
     *
     * @return string
     */
    public function __toString(): string;
}
