<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Provider;

use TrueLayer\Entities\Entity;
use TrueLayer\Interfaces\Provider\PaymentsProviderInterface;

final class PaymentsProvider extends Entity implements PaymentsProviderInterface
{
    /**
     * @var string
     */
    protected string $id;

    /**
     * @var string|null
     */
    protected ?string $displayName = null;

    /**
     * @var string|null
     */
    protected ?string $iconUri = null;

    /**
     * @var string|null
     */
    protected ?string $logoUri = null;

    /**
     * @var string|null
     */
    protected ?string $bgColor = null;

    /**
     * @var string|null
     */
    protected ?string $countryCode = null;

    /**
     * @var string|null
     */
    protected ?string $swiftCode = null;

    /**
     * @var array<string, mixed>|null
     */
    protected ?array $binRanges = null;

    /**
     * @var array<string, mixed>
     */
    protected array $capabilities = [];

    /**
     * @var array|string[]
     */
    protected array $arrayFields = [
        'id',
        'display_name',
        'icon_uri',
        'logo_uri',
        'bg_color',
        'country_code',
        'swift_code',
        'bin_ranges',
        'capabilities',
    ];

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getDisplayName(): ?string
    {
        return $this->displayName;
    }

    /**
     * @return string|null
     */
    public function getIconUri(): ?string
    {
        return $this->iconUri;
    }

    /**
     * @return string|null
     */
    public function getLogoUri(): ?string
    {
        return $this->logoUri;
    }

    /**
     * @return string|null
     */
    public function getBgColor(): ?string
    {
        return $this->bgColor;
    }

    /**
     * @return string|null
     */
    public function getBgColour(): ?string
    {
        return $this->getBgColor();
    }

    /**
     * @return string|null
     */
    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    /**
     * @return string|null
     */
    public function getSwiftCode(): ?string
    {
        return $this->swiftCode;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getBinRanges(): ?array
    {
        return $this->binRanges;
    }

    /**
     * @return array<string, mixed>
     */
    public function getCapabilities(): array
    {
        return $this->capabilities;
    }
}