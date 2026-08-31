<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\PaymentsProvider;

use TrueLayer\Interfaces\ArrayableInterface;
use TrueLayer\Interfaces\HasAttributesInterface;

interface PaymentsProviderInterface extends ArrayableInterface, HasAttributesInterface
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
    public function getCountryCode(): ?string;

    /**
     * @return string|null
     */
    public function getSwiftCode(): ?string;

    /**
     * @return mixed[]
     */
    public function getCapabilities(): array;

    /**
     * @return mixed[]|null
     */
    public function getBinRanges(): ?array;
}
