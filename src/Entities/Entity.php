<?php

declare(strict_types=1);

namespace TrueLayer\Entities;

use TrueLayer\Attributes\ArrayShape;
use TrueLayer\Attributes\Field;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Interfaces\ArrayableInterface;
use TrueLayer\Interfaces\Factories\EntityFactoryInterface;
use TrueLayer\Interfaces\HasAttributesInterface;
use TrueLayer\Services\Util\Str;
use TrueLayer\Traits\ArrayableAttributes;
use TrueLayer\Traits\CastsAttributes;

abstract class Entity implements ArrayableInterface, HasAttributesInterface
{
    use CastsAttributes;
    use ArrayableAttributes;

    private array $propertyFieldMap = [];
    private array $propertyCastMap = [];


    /**
     * @var EntityFactoryInterface
     */
    protected EntityFactoryInterface $entityFactory;

    /**
     * @param EntityFactoryInterface $entityFactory
     */
    public function __construct(
        EntityFactoryInterface $entityFactory
    )
    {
        $this->entityFactory = $entityFactory;
        $this->buildFieldAndCastMaps();
    }

    /**
     * @param mixed[] $data
     *
     * @return $this
     * @throws InvalidArgumentException
     *
     */
    public function fill(array $data): self
    {
        $data = $this->castData($data);
        $this->setValues($data);

        return $this;
    }

    /**
     * @template T
     *
     * @param class-string<T> $abstract
     * @param mixed[]|null $data
     *
     * @return T
     * @throws InvalidArgumentException
     *
     */
    protected function make(string $abstract, ?array $data = null)
    {
        return $this->entityFactory->make($abstract, $data);
    }

    /**
     * @template T
     *
     * @param class-string<T> $abstract
     * @param mixed[]|null $data
     *
     * @return T[]
     * @throws InvalidArgumentException
     *
     */
    protected function makeMany(string $abstract, ?array $data = null)
    {
        return $data ?
            $this->entityFactory->makeMany($abstract, $data)
            : [];
    }

    private function buildFieldAndCastMaps(): void
    {
        $reflectionClass = new \ReflectionClass(get_called_class());

        foreach ($reflectionClass->getProperties() as $property) {
            $fieldAttributes = $property->getAttributes(Field::class);

            // If the property is not annotated with the Field attribute, we skip it.
            if (empty($fieldAttributes)) {
                continue;
            }

            $propertyName = $property->getName();
            $dotNotationFieldName = $fieldAttributes[0]->newInstance()->name ?? Str::snake($propertyName);;
            $this->propertyFieldMap[$dotNotationFieldName] = $propertyName;

            $arrayTypeAttributes = $property->getAttributes(ArrayShape::class);
            if (!empty($arrayTypeAttributes)) {
                $this->propertyCastMap["{$dotNotationFieldName}.*"] = $arrayTypeAttributes[0]->newInstance()->type;
            } else {
                $propertyType = $property->getType();
                if ($propertyType instanceof \ReflectionNamedType) {
                    $this->propertyCastMap[$dotNotationFieldName] = $propertyType->getName();
                }
            }
        }
    }
}
