<?php

declare(strict_types=1);

namespace TrueLayer\Entities;

use Illuminate\Support\Str;
use TrueLayer\Exceptions\ValidationException;
use TrueLayer\Interfaces\HppInterface;

final class Hpp extends Entity implements HppInterface
{
    /**
     * @var string
     */
    protected string $baseUrl;

    /**
     * @var string
     */
    protected string $paymentId;

    /**
     * @var string
     */
    protected string $resourceToken;

    /**
     * @var string
     */
    protected string $returnUri;

    /**
     * @var string
     */
    protected string $primaryColour;

    /**
     * @var string
     */
    protected string $secondaryColour;

    /**
     * @var string
     */
    protected string $tertiaryColour;

    /**
     * @var string[]
     */
    protected array $arrayFields = [
        'base_url',
        'payment_id',
        'resource_token' => 'resource_token',
        'return_uri',
        'c_primary' => 'primary_colour',
        'c_secondary' => 'secondary_colour',
        'c_tertiary' => 'tertiary_colour',
    ];

    /**
     * @var string[]
     */
    protected array $rules = [
        'base_url' => 'required|url',
        'payment_id' => 'required|string',
        'resource_token' => 'required|string',
        'return_uri' => 'required|url',
        'c_primary' => 'regex:/^([0-9A-F]{3}){1,2}$/i',
        'c_secondary' => 'regex:/^([0-9A-F]{3}){1,2}$/i',
        'c_tertiary' => 'regex:/^([0-9A-F]{3}){1,2}$/i',
    ];

    /**
     * @param string $baseUrl
     *
     * @return HppInterface
     */
    public function baseUrl(string $baseUrl): HppInterface
    {
        $this->baseUrl = $baseUrl;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getBaseUrl(): ?string
    {
        return $this->baseUrl ?? null;
    }

    /**
     * @param string $paymentId
     *
     * @return HppInterface
     */
    public function paymentId(string $paymentId): HppInterface
    {
        $this->paymentId = $paymentId;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPaymentId(): ?string
    {
        return $this->paymentId ?? null;
    }

    /**
     * @param string $resourceToken
     *
     * @return HppInterface
     */
    public function resourceToken(string $resourceToken): HppInterface
    {
        $this->resourceToken = $resourceToken;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getResourceToken(): ?string
    {
        return $this->resourceToken ?? null;
    }

    /**
     * @param string $returnUri
     *
     * @return HppInterface
     */
    public function returnUri(string $returnUri): HppInterface
    {
        $this->returnUri = $returnUri;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getReturnUri(): ?string
    {
        return $this->returnUri ?? null;
    }

    /**
     * @param string $hex
     *
     * @return HppInterface
     */
    public function primaryColour(string $hex): HppInterface
    {
        $this->primaryColour = $this->colour($hex);

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPrimaryColour(): ?string
    {
        return $this->primaryColour ?? null;
    }

    /**
     * @param string $hex
     *
     * @return HppInterface
     */
    public function secondaryColour(string $hex): HppInterface
    {
        $this->secondaryColour = $this->colour($hex);

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSecondaryColour(): ?string
    {
        return $this->secondaryColour ?? null;
    }

    /**
     * @param string $hex
     *
     * @return HppInterface
     */
    public function tertiaryColour(string $hex): HppInterface
    {
        $this->tertiaryColour = $this->colour($hex);

        return $this;
    }

    /**
     * @return string|null
     */
    public function getTertiaryColour(): ?string
    {
        return $this->tertiaryColour ?? null;
    }

    /**
     * @throws ValidationException
     *
     * @return string
     */
    public function toUrl(): string
    {
        $params = $this->validate()->toArray();
        unset($params['base_url']);

        return $this->baseUrl . '#' . \http_build_query(
            $params, '', '&', PHP_QUERY_RFC3986
        );
    }

    /**
     * @throws ValidationException
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->toUrl();
    }

    /**
     * @param string $hex
     *
     * @return string
     */
    protected function colour(string $hex): string
    {
        return Str::after($hex, '#');
    }
}
