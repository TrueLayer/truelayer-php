<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Provider;

interface PaymentsProviderInterface
{
    /**
     * @return string
     */
    public function getId(): string;

    /**
     * @return string|null
     */
    public function getDisplayName(): ?string;

    /**
     * @return string|null
     */
    public function getIconUri(): ?string;

    /**
     * @return string|null
     */
    public function getLogoUri(): ?string;

    /**
     * @return string|null
     */
    public function getBgColor(): ?string;

    /**
     * @return string|null
     */
    public function getBgColour(): ?string;

    /**
     * @return string|null
     */
    public function getCountryCode(): ?string;

    /**
     * @return string|null
     */
    public function getSwiftCode(): ?string;

    /**
     * @return array<string, mixed>|null
     */
    public function getBinRanges(): ?array;

    /**
     * @return array<string, mixed>
     */
    public function getCapabilities(): array;
}