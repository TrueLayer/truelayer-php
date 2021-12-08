<?php

declare(strict_types=1);

namespace TrueLayer\Services\Hpp;

use Illuminate\Support\Str;
use Illuminate\Contracts\Validation\Factory as ValidatorFactory;
use TrueLayer\Contracts\Hpp\HppHelperInterface;
use TrueLayer\Traits\HasAttributes;
use TrueLayer\Exceptions\ValidationException;

class HppHelper implements HppHelperInterface
{
    use HasAttributes;

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
     * @param string $baseUrl
     */
    public function __construct(ValidatorFactory $validatorFactory, string $baseUrl)
    {
        $this->validatorFactory = $validatorFactory;
        $this->baseUrl = $baseUrl;
    }

    /**
     * @return string
     * @throws ValidationException
     */
    public function __toString(): string
    {
        return $this->toString();
    }

    /**
     * @return string
     * @throws ValidationException
     */
    public function toString(): string
    {
        return $this->baseUrl . $this->getHashQuery();
    }

    /**
     * @param string $paymentId
     * @return HppHelperInterface
     */
    public function paymentId(string $paymentId): HppHelperInterface
    {
        return $this->set('payment_id', $paymentId);
    }

    /**
     * @param string $resourceToken
     * @return HppHelperInterface
     */
    public function resourceToken(string $resourceToken): HppHelperInterface
    {
        return $this->set('resource_token', $resourceToken);
    }

    /**
     * @param string $returnUri
     * @return HppHelperInterface
     */
    public function returnUri(string $returnUri): HppHelperInterface
    {
        return $this->set('return_uri', $returnUri);
    }

    /**
     * @param string $hex
     * @return HppHelperInterface
     */
    public function primary(string $hex): HppHelperInterface
    {
        return $this->color('c_primary', $hex);
    }

    /**
     * @param string $hex
     * @return HppHelperInterface
     */
    public function secondary(string $hex): HppHelperInterface
    {
        return $this->color('c_secondary', $hex);
    }

    /**
     * @param string $hex
     * @return HppHelperInterface
     */
    public function tertiary(string $hex): HppHelperInterface
    {
        return $this->color('c_tertiary', $hex);
    }

    /**
     * Redirect the user to the Hosted Payments Page
     */
    public function redirect(): void
    {
        header("Location: {$this->__toString()}");
        die();
    }

    /**
     * @param string $key
     * @param string $hex
     * @return HppHelperInterface
     */
    private function color(string $key, string $hex): HppHelperInterface
    {
        return $this->set($key, Str::after($hex,'#'));
    }

    /**
     * @return string
     * @throws ValidationException
     */
    private function getHashQuery(): string
    {
        return '#' . http_build_query(
            $this->getValidatedHashParams(), '', '&', PHP_QUERY_RFC3986
        );
    }

    /**
     * @return array
     * @throws ValidationException
     */
    private function getValidatedHashParams(): array
    {
        $hexRules = 'string|alpha_num|min:3|max:8';

        $validator = $this->validatorFactory->make($this->toArray(), [
            'payment_id' => 'required|string',
            'resource_token' => 'required|string',
            'return_uri' => 'required|url',
            'c_primary' => $hexRules,
            'c_secondary' => $hexRules,
            'c_tertiary' => $hexRules,
        ]);

        try {
            return $validator->validate();
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw new ValidationException($validator);
        }
    }
}