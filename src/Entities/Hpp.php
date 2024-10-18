<?php

declare(strict_types=1);

namespace TrueLayer\Entities;

use TrueLayer\Attributes\Field;
use TrueLayer\Interfaces\HppInterface;
use TrueLayer\Services\Util\Str;

final class Hpp extends Entity implements HppInterface
{
    /**
     * @var string
     */
    #[Field]
    protected string $baseUrl;

    /**
     * @var string
     */
    #[Field]
    protected string $paymentId;

    /**
     * @var string
     */
    #[Field]
    protected string $resourceToken;

    /**
     * @var string
     */
    #[Field]
    protected string $returnUri;

    /**
     * @var string
     */
    #[Field('c_primary')]
    protected string $primaryColour;

    /**
     * @var string
     */
    #[Field('c_secondary')]
    protected string $secondaryColour;

    /**
     * @var string
     */
    #[Field('c_tertiary')]
    protected string $tertiaryColour;

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
     * @return string
     */
    public function toUrl(): string
    {
        $params = $this->toArray();
        unset($params['base_url']);

        return $this->baseUrl . '#' . \http_build_query(
                $params, '', '&', PHP_QUERY_RFC3986
            );
    }

    /**
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
