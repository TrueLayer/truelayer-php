<?php

declare(strict_types=1);

namespace TrueLayer\Entities\PaymentsProvider;

use TrueLayer\Entities\Entity;
use TrueLayer\Interfaces\PaymentsProvider\SearchCapabilitiesInterface;

class SearchCapabilities extends Entity implements SearchCapabilitiesInterface
{
    /**
     * @var array<string, mixed>
     */
    protected array $payments;

    /**
     * @var array<string, mixed>
     */
    protected array $mandates;

    /**
     * @var array<string>
     */
    protected array $arrayFields = [
        'payments',
        'mandates',
    ];

    /**
     * @return $this
     */
    public function payments(): self
    {
        if (!isset($this->payments)) {
            $this->payments = [];
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function bankTransfer(): self
    {
        $this->payments();
        $this->payments['bank_transfer'] = (object) [];

        return $this;
    }

    /**
     * @return $this
     */
    public function mandates(): self
    {
        if (!isset($this->mandates)) {
            $this->mandates = [];
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function vrpCommercial(): self
    {
        $this->mandates();
        $this->mandates['vrp_commercial'] = (object) [];

        return $this;
    }

    /**
     * @return $this
     */
    public function vrpSweeping(): self
    {
        $this->mandates();
        $this->mandates['vrp_sweeping'] = (object) [];

        return $this;
    }
}
