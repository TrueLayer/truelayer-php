<?php

declare(strict_types=1);

namespace TrueLayer\Contracts\Hpp;

use TrueLayer\Exceptions\ValidationException;

interface HppHelperInterface
{
    /**
     * @return string
     */
    public function __toString(): string;

    /**
     * @throws ValidationException
     *
     * @return string
     */
    public function toString(): string;

    /**
     * @param string $paymentId
     *
     * @return HppHelperInterface
     */
    public function paymentId(string $paymentId): HppHelperInterface;

    /**
     * @param string $resourceToken
     *
     * @return HppHelperInterface
     */
    public function resourceToken(string $resourceToken): HppHelperInterface;

    /**
     * @param string $returnUri
     *
     * @return HppHelperInterface
     */
    public function returnUri(string $returnUri): HppHelperInterface;

    /**
     * @param string $hex
     *
     * @return HppHelperInterface
     */
    public function primary(string $hex): HppHelperInterface;

    /**
     * @param string $hex
     *
     * @return HppHelperInterface
     */
    public function secondary(string $hex): HppHelperInterface;

    /**
     * @param string $hex
     *
     * @return HppHelperInterface
     */
    public function tertiary(string $hex): HppHelperInterface;

    /**
     * Redirect the user to the Hosted Payments Page.
     */
    public function redirect(): void;
}
