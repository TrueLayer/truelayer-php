<?php

declare(strict_types=1);

namespace TrueLayer\Entities\PaymentsProvider;

use TrueLayer\Entities\Entity;
use TrueLayer\Interfaces\PaymentsProvider\AuthorizationFlowConfigurationInterface;

class AuthorizationFlowConfiguration extends Entity implements AuthorizationFlowConfigurationInterface
{
    /**
     * @var array<string, mixed>|\stdClass
     */
    protected $redirect;

    /**
     * @var array<string, mixed>|\stdClass
     */
    protected $providerSelection;

    /**
     * @var array<string, mixed>
     */
    protected array $form;

    /**
     * @var array<string, mixed>
     */
    protected array $consent;

    /**
     * @var array<string>
     */
    protected array $arrayFields = [
        'redirect',
        'provider_selection',
        'form',
        'consent',
    ];

    /**
     * @return $this
     */
    public function redirect(): self
    {
        $this->redirect = (object) [];

        return $this;
    }

    /**
     * @return $this
     */
    public function providerSelection(): self
    {
        $this->providerSelection = (object) [];

        return $this;
    }

    /**
     * @param string $size
     *
     * @return $this
     */
    public function providerSelectionWithIcon(string $size): self
    {
        $this->providerSelection = [
            'icon' => [
                'size' => $size,
            ],
        ];

        return $this;
    }

    /**
     * @param string[] $inputTypes
     *
     * @return $this
     */
    public function form(array $inputTypes): self
    {
        $this->form = [
            'input_types' => $inputTypes,
        ];

        return $this;
    }

    /**
     * @param string $requirements
     *
     * @return $this
     */
    public function consent(string $requirements): self
    {
        $this->consent = [
            'requirements' => $requirements,
        ];

        return $this;
    }
}
