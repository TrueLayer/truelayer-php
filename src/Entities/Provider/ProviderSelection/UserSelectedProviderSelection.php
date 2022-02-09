<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Provider\ProviderSelection;

use TrueLayer\Constants\ProviderSelectionTypes;
use TrueLayer\Entities\Entity;
use TrueLayer\Interfaces\Provider\ProviderFilterInterface;
use TrueLayer\Interfaces\Provider\UserSelectedProviderSelectionInterface;
use TrueLayer\Validation\ValidType;

final class UserSelectedProviderSelection extends Entity implements UserSelectedProviderSelectionInterface
{
    /**
     * @var ProviderFilterInterface
     */
    protected ProviderFilterInterface $filter;

    /**
     * @var mixed[]
     */
    protected array $casts = [
        'filter' => ProviderFilterInterface::class,
    ];

    /**
     * @var string[]
     */
    protected array $arrayFields = [
        'type',
        'filter',
    ];

    /**
     * @return mixed[]
     */
    protected function rules(): array
    {
        return [
            'filter' => ['nullable', ValidType::of(ProviderFilterInterface::class)],
        ];
    }

    /**
     * @return ProviderFilterInterface|null
     */
    public function getFilter(): ?ProviderFilterInterface
    {
        return $this->filter ?? null;
    }

    /**
     * @param ProviderFilterInterface $filter
     *
     * @return $this
     */
    public function filter(ProviderFilterInterface $filter): self
    {
        $this->filter = $filter;

        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return ProviderSelectionTypes::USER_SELECTED;
    }
}
