<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\PaymentsProvider;

use TrueLayer\Interfaces\ArrayableInterface;
use TrueLayer\Interfaces\HasAttributesInterface;

interface SearchCapabilitiesInterface extends ArrayableInterface, HasAttributesInterface
{
    /**
     * @return $this
     */
    public function payments(): self;

    /**
     * @return $this
     */
    public function bankTransfer(): self;

    /**
     * @return $this
     */
    public function mandates(): self;

    /**
     * @return $this
     */
    public function vrpCommercial(): self;

    /**
     * @return $this
     */
    public function vrpSweeping(): self;
}
