<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\PaymentsProvider;

use TrueLayer\Interfaces\ArrayableInterface;
use TrueLayer\Interfaces\HasAttributesInterface;

interface AuthorizationFlowConfigurationInterface extends ArrayableInterface, HasAttributesInterface
{
    /**
     * @return $this
     */
    public function redirect(): self;

    /**
     * @return $this
     */
    public function providerSelection(): self;

    /**
     * @param string $size
     *
     * @return $this
     */
    public function providerSelectionWithIcon(string $size): self;

    /**
     * @param string[] $inputTypes
     *
     * @return $this
     */
    public function form(array $inputTypes): self;

    /**
     * @param string $requirements
     *
     * @return $this
     */
    public function consent(string $requirements): self;
}
