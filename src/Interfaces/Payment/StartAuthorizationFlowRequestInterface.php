<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Payment;

use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Exceptions\SignerException;
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
     * If you are building a custom UI, you need to specify whether your UI can render a provider selection screen.
     * This is mandatory for user selected provider selection. For preselected, it's optional.
     * {@see \TrueLayer\Interfaces\Provider\UserSelectedProviderSelectionInterface}
     * Use the {@see \TrueLayer\Interfaces\Client\ClientInterface::providerFilter()} parameter
     * at payment creation to determine the list of providers you can choose to display in your UI.
     */
    public function enableProviderSelection(): StartAuthorizationFlowRequestInterface;

    /**
     * @return StartAuthorizationFlowRequestInterface
     * If you are building a custom UI, you need to specify whether your UI can render a provider selection screen.
     * This is mandatory for user selected scheme selection. For preselected, it's optional.
     * {@see \TrueLayer\Interfaces\Scheme\UserSelectedSchemeSelectionInterface}
     */
    public function enableSchemeSelection(): StartAuthorizationFlowRequestInterface;

    /**
     * @return StartAuthorizationFlowRequestInterface
     * If you are building a custom UI, you need to specify whether your UI can render a user account selection screen.
     * If the user has previously consented to saving their bank account details with TrueLayer,
     * they can choose from their saved accounts to speed up following payments.
     */
    public function enableUserAccountSelection(): StartAuthorizationFlowRequestInterface;

    /**
     * @param string $returnUri The URL where the user should be redirected back once the flow on the third-party's website is completed.
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
     * @throws SignerException
     * @throws ApiResponseUnsuccessfulException
     * @throws InvalidArgumentException
     *
     * @return AuthorizationFlowResponseInterface
     */
    public function start(): AuthorizationFlowResponseInterface;
}
