<?php

declare(strict_types=1);

namespace TrueLayer\Entities;

use Illuminate\Contracts\Validation\Factory as ValidatorFactory;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Exceptions\ValidationException;
use TrueLayer\Interfaces\ArrayableInterface;
use TrueLayer\Interfaces\Factories\ApiFactoryInterface;
use TrueLayer\Interfaces\Factories\EntityFactoryInterface;
use TrueLayer\Interfaces\HasAttributesInterface;
use TrueLayer\Traits\ArrayableAttributes;
use TrueLayer\Traits\CastsAttributes;
use TrueLayer\Traits\ValidatesAttributes;

abstract class Entity implements ArrayableInterface, HasAttributesInterface
{
    use ValidatesAttributes;
    use CastsAttributes;
    use ArrayableAttributes;

    /**
     * @var EntityFactoryInterface
     */
    private EntityFactoryInterface $entityFactory;

    /**
     * @var ApiFactoryInterface
     */
    private ApiFactoryInterface $apiFactory;

    /**
     * @param ValidatorFactory       $validatorFactory
     * @param EntityFactoryInterface $entityFactory
     * @param ApiFactoryInterface    $apiFactory
     */
    public function __construct(
        ValidatorFactory $validatorFactory,
        EntityFactoryInterface $entityFactory,
        ApiFactoryInterface $apiFactory
    ) {
        $this->validatorFactory = $validatorFactory;
        $this->entityFactory = $entityFactory;
        $this->apiFactory = $apiFactory;
    }

    /**
     * @param mixed[] $data
     *
     * @throws InvalidArgumentException
     * @throws ValidationException
     *
     * @return $this
     */
    public function fill(array $data): self
    {
        $data = $this->castData($data);
        $this->validateData($data);
        $this->setValues($data);

        return $this;
    }

    /**
     * @return ApiFactoryInterface
     */
    protected function apiFactory(): ApiFactoryInterface
    {
        return $this->apiFactory;
    }

    /**
     * @template T
     *
     * @param class-string<T> $abstract
     * @param mixed[]|null    $data
     *
     * @throws InvalidArgumentException
     * @throws ValidationException
     *
     * @return T
     */
    protected function make(string $abstract, array $data = null)
    {
        return $this->entityFactory->make($abstract, $data);
    }
}
