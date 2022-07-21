<?php

declare(strict_types=1);

namespace TrueLayer\Factories;

use Illuminate\Contracts\Validation\Factory as ValidatorFactory;
use Illuminate\Support\Arr;
use TrueLayer\Constants\Endpoints;
use TrueLayer\Entities;
use TrueLayer\Entities\Hpp;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Exceptions\ValidationException;
use TrueLayer\Interfaces;

final class EntityFactory implements Interfaces\Factories\EntityFactoryInterface
{
    /**
     * @var ValidatorFactory
     */
    private ValidatorFactory $validatorFactory;

    /**
     * @var Interfaces\Factories\ApiFactoryInterface|null
     */
    private ?Interfaces\Factories\ApiFactoryInterface $apiFactory;

    /**
     * @var Interfaces\Configuration\ConfigInterface
     */
    private Interfaces\Configuration\ConfigInterface $sdkConfig;

    /**
     * @var array<string, string>
     */
    private array $bindings;

    /**
     * @var array<string, array<string, string>>
     */
    private array $discriminations;

    /**
     * @param ValidatorFactory                              $validatorFactory
     * @param Interfaces\Configuration\ConfigInterface      $sdkConfig
     * @param Interfaces\Factories\ApiFactoryInterface|null $apiFactory
     */
    public function __construct(
        ValidatorFactory $validatorFactory,
        Interfaces\Configuration\ConfigInterface $sdkConfig,
        Interfaces\Factories\ApiFactoryInterface $apiFactory = null
    ) {
        $this->validatorFactory = $validatorFactory;
        $this->sdkConfig = $sdkConfig;
        $this->apiFactory = $apiFactory;

        $configPath = \dirname(__FILE__, 3) . '/config';
        $this->bindings = include "{$configPath}/bindings.php";
        $this->discriminations = include "{$configPath}/discriminations.php";
    }

    /**
     * @template T of object
     *
     * @param class-string<T> $abstract
     * @param mixed[]|null    $data
     *
     * @throws InvalidArgumentException
     * @throws ValidationException
     *
     * @return T
     * @return T implements
     */
    public function make(string $abstract, array $data = null)
    {
        $abstract = $this->getTypeAbstract($abstract, $data);
        $concrete = $this->bindings[$abstract] ?? null;

        if (!$concrete) {
            throw new InvalidArgumentException("Could not find concrete implementation for {$abstract}");
        }

        if (\method_exists($this, $concrete)) {
            return $this->{$concrete}($data);
        }

        if (!\class_exists($concrete)) {
            throw new InvalidArgumentException("Could not find class {$concrete}");
        }

        $instance = $this->makeConcrete($concrete);

        if ($data && $instance instanceof Interfaces\HasAttributesInterface) {
            $instance->fill($data);
        }

        // @phpstan-ignore-next-line
        return $instance;
    }

    /**
     * @template T of object
     *
     * @param class-string<T> $abstract
     * @param mixed[]         $data
     *
     * @throws ValidationException
     * @throws InvalidArgumentException
     *
     * @return T[]
     */
    public function makeMany(string $abstract, array $data): array
    {
        return \array_map(function ($item) use ($abstract) {
            if (!\is_array($item)) {
                throw new InvalidArgumentException('Item is not array');
            }

            return $this->make($abstract, $item);
        }, $data);
    }

    /**
     * @throws InvalidArgumentException
     *
     * @return Interfaces\HppInterface
     */
    private function makeHpp(): Interfaces\HppInterface
    {
        $baseUrl = $this->sdkConfig->shouldUseProduction()
            ? Endpoints::HPP_PROD_URL
            : Endpoints::HPP_SANDBOX_URL;

        return $this->makeConcrete(Hpp::class)->baseUrl($baseUrl);
    }

    /**
     * @template T of object
     *
     * @param class-string<T> $concrete
     *
     * @throws InvalidArgumentException
     *
     * @return T
     */
    public function makeConcrete(string $concrete)
    {
        // We could just return the new instances but PHPStan doesn't understand
        // is_subclass_of so we need to rely on the instanceof operator.
        $instance = null;

        if (\is_subclass_of($concrete, Entities\Entity::class)) {
            $instance = new $concrete($this->validatorFactory, $this);
        } elseif (\is_subclass_of($concrete, Entities\EntityBuilder::class)) {
            $instance = new $concrete($this);
        }

        if ($instance instanceof Interfaces\HasApiFactoryInterface) {
            if (!$this->apiFactory) {
                throw new InvalidArgumentException("{$concrete} requires ApiFactory but none provided");
            }

            $instance->apiFactory($this->apiFactory);
        }

        if ($instance instanceof $concrete) {
            return $instance;
        }

        throw new InvalidArgumentException("Provided concrete class {$concrete} must be an Entity or EntityBuilder");
    }

    /**
     * Recursively look in the discriminations array to find an abstract based on the provided data.
     *
     * @param class-string $abstract
     * @param mixed[]|null $data
     *
     * @return class-string
     */
    private function getTypeAbstract(string $abstract, array $data = null): string
    {
        if (isset($this->discriminations[$abstract]) && !empty($data)) {
            $typeConfig = $this->discriminations[$abstract];

            // Get the "type" of the model based on the provided discriminate_on
            $key = $typeConfig['discriminate_on'];
            $type = Arr::get($data, $key);

            if (\is_string($type) && isset($typeConfig[$type])) {
                $typeAbstract = $typeConfig[$type];
                if (\interface_exists($typeAbstract) || \class_exists($typeAbstract)) {
                    return $this->getTypeAbstract($typeAbstract, $data);
                }
            }
        }

        return $abstract;
    }
}
