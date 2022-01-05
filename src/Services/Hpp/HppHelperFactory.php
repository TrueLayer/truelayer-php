<?php

declare(strict_types=1);

namespace TrueLayer\Services\Hpp;

use Illuminate\Contracts\Validation\Factory as ValidatorFactory;
use TrueLayer\Contracts\Hpp\HppHelperFactoryInterface;
use TrueLayer\Contracts\Hpp\HppHelperInterface;

final class HppHelperFactory implements HppHelperFactoryInterface
{
    /**
     * @var ValidatorFactory
     */
    private ValidatorFactory $validatorFactory;

    /**
     * @var string
     */
    private string $baseUrl;

    /**
     * @param ValidatorFactory $validatorFactory
     * @param string           $baseUrl
     */
    public function __construct(ValidatorFactory $validatorFactory, string $baseUrl)
    {
        $this->validatorFactory = $validatorFactory;
        $this->baseUrl = $baseUrl;
    }

    /**
     * @return HppHelperInterface
     */
    public function make(): HppHelperInterface
    {
        return new HppHelper($this->validatorFactory, $this->baseUrl);
    }
}
