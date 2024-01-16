<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Payment;

use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Exceptions\SignerException;
use TrueLayer\Exceptions\ValidationException;
use TrueLayer\Interfaces\HasAttributesInterface;
use TrueLayer\Interfaces\Payment\AuthorizationFlow\AuthorizationFlowResponseInterface;

interface StartAuthorizationFlowRequestInterface extends HasAttributesInterface
{
    /**
     * @param string $paymentId
     *
     * @return StartAuthorizationFlowRequestInterface
     */
    public function paymentId(string $paymentId): StartAuthorizationFlowRequestInterface;

    /**
     * @return StartAuthorizationFlowRequestInterface
     */
    public function useHPPCapabilities(): StartAuthorizationFlowRequestInterface;

    /**
     * @return StartAuthorizationFlowRequestInterface
     */
    public function enableProviderSelection(): StartAuthorizationFlowRequestInterface;

    /**
     * @return StartAuthorizationFlowRequestInterface
     */
    public function enableSchemeSelection(): StartAuthorizationFlowRequestInterface;

    /**
     * @return StartAuthorizationFlowRequestInterface
     */
    public function enableUserAccountSelection(): StartAuthorizationFlowRequestInterface;

    /**
     * @param string $returnUri
     *
     * @return StartAuthorizationFlowRequestInterface
     */
    public function returnUri(string $returnUri): StartAuthorizationFlowRequestInterface;

    /**
     * @param string $directReturnUri
     *
     * @return StartAuthorizationFlowRequestInterface
     */
    public function directReturnUri(string $directReturnUri): StartAuthorizationFlowRequestInterface;

    /**
     * @param string[] $types
     *
     * @return StartAuthorizationFlowRequestInterface
     */
    public function formInputTypes(array $types): StartAuthorizationFlowRequestInterface;

    /**
     * @throws InvalidArgumentException
     * @throws SignerException
     * @throws ValidationException
     * @throws ApiResponseUnsuccessfulException
     *
     * @return AuthorizationFlowResponseInterface
     */
    public function start(): AuthorizationFlowResponseInterface;
}
