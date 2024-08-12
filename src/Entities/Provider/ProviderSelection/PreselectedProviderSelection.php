<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Provider\ProviderSelection;

use TrueLayer\Constants\ProviderSelectionTypes;
use TrueLayer\Entities\Entity;
use TrueLayer\Interfaces\Provider\PreselectedProviderSelectionInterface;
use TrueLayer\Interfaces\Remitter\RemitterInterface;
use TrueLayer\Interfaces\Scheme\SchemeSelectionInterface;

final class PreselectedProviderSelection extends Entity implements PreselectedProviderSelectionInterface
{
    /**
     * @var string
     */
    protected string $providerId;

    /**
     * @var SchemeSelectionInterface
     */
    protected SchemeSelectionInterface $schemeSelection;

    /**
     * @var string
     */
    protected string $dataAccessToken;

    /**
     * @var RemitterInterface
     */
    protected RemitterInterface $remitter;

    /**
     * @var mixed[]
     */
    protected array $casts = [
        'scheme_selection' => SchemeSelectionInterface::class,
        'remitter' => RemitterInterface::class,
    ];

    /**
     * @var string[]
     */
    protected array $arrayFields = [
        'type',
        'provider_id',
        'scheme_selection',
        'remitter',
        'data_access_token',
    ];

    /**
     * @return string|null
     */
    public function getProviderId(): ?string
    {
        return $this->providerId ?? null;
    }

    /**
     * @param string $providerId
     *
     * @return $this
     */
    public function providerId(string $providerId): self
    {
        $this->providerId = $providerId;

        return $this;
    }

    /**
     * @return SchemeSelectionInterface|null
     */
    public function getSchemeSelection(): ?SchemeSelectionInterface
    {
        return $this->schemeSelection ?? null;
    }

    /**
     * @param SchemeSelectionInterface $schemeSelection
     *
     * @return $this
     */
    public function schemeSelection(SchemeSelectionInterface $schemeSelection): self
    {
        $this->schemeSelection = $schemeSelection;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDataAccessToken(): ?string
    {
        return $this->dataAccessToken ?? null;
    }

    /**
     * @param string $dataAccessToken
     *
     * @return $this
     */
    public function dataAccessToken(string $dataAccessToken): self
    {
        $this->dataAccessToken = $dataAccessToken;

        return $this;
    }

    /**
     * @return RemitterInterface|null
     */
    public function getRemitter(): ?RemitterInterface
    {
        return $this->remitter ?? null;
    }

    /**
     * @param RemitterInterface $remitter
     *
     * @return $this
     */
    public function remitter(RemitterInterface $remitter): self
    {
        $this->remitter = $remitter;

        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return ProviderSelectionTypes::PRESELECTED;
    }
}
