<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Provider;

use TrueLayer\Constants\Countries;
use TrueLayer\Interfaces\Provider\ProviderInterface;
use TrueLayer\Entities\Entity;
use TrueLayer\Validation\AllowedConstant;

final class Provider extends Entity implements ProviderInterface
{
    /**
     * @var string
     */
    protected string $providerId;

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
        'provider_id',
        'display_name',
        'icon_uri',
        'logo_uri',
        'bg_color',
        'country_code',
    ];

    /**
     * @return mixed[]
     */
    protected function rules(): array
    {
        return [
            'provider_id' => 'string',
            'display_name' => 'string',
            'icon_uri' => 'url',
            'logo_uri' => 'url',
            'bg_color' => 'regex:/^#[a-fA-F0-9]{6}$/',
            'country_code' => AllowedConstant::in(Countries::class),
        ];
    }

    /**
     * @return string|null
     */
    public function getProviderId(): ?string
    {
        return $this->providerId ?? null;
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
