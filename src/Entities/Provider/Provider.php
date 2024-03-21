<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Provider;

use TrueLayer\Entities\Entity;
use TrueLayer\Interfaces\Provider\ProviderInterface;

final class Provider extends Entity implements ProviderInterface
{
    /**
     * @var string
     */
    protected string $id;

    /**
     * @var string
     */
    protected string $displayName;

    /**
     * @var string
     */
    protected string $iconUri;

    /**
     * @var string
     */
    protected string $logoUri;

    /**
     * @var string
     */
    protected string $bgColor;

    /**
     * @var string
     */
    protected string $countryCode;

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
    ];

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id ?? null;
    }

    /**
     * @return string|null
     */
    public function getDisplayName(): ?string
    {
        return $this->displayName ?? null;
    }

    /**
     * @return string|null
     */
    public function getIconUri(): ?string
    {
        return $this->iconUri ?? null;
    }

    /**
     * @return string|null
     */
    public function getLogoUri(): ?string
    {
        return $this->logoUri ?? null;
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
    public function getBgColor(): ?string
    {
        return $this->bgColor ?? null;
    }

    /**
     * @return string|null
     */
    public function getCountryCode(): ?string
    {
        return $this->countryCode ?? null;
    }
}
