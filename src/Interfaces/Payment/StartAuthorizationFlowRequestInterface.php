<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Payment;

use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Exceptions\SignerException;
use TrueLayer\Interfaces\HasAttributesInterface;
use TrueLayer\Interfaces\Payment\AuthorizationFlow\AuthorizationFlowResponseInterface;

/**
 * Builds a request to manually start the authorization flow for a payment, declaring which parts of the
 * flow your UI is able to support. Cannot be used if `hosted_page` was requested on payment creation.
 */
interface StartAuthorizationFlowRequestInterface extends HasAttributesInterface
{
    /**
     * @param string $paymentId
     *
     * @return StartAuthorizationFlowRequestInterface
     */
    public function paymentId(string $paymentId): StartAuthorizationFlowRequestInterface;

    /**
     * Declares that your UI can hand over to the Hosted Payments Page instead of rendering the
     * authorization flow steps yourself.
     *
     * @return StartAuthorizationFlowRequestInterface
     */
    public function useHPPCapabilities(): StartAuthorizationFlowRequestInterface;

    /**
     * Declares that your UI can render a provider selection screen. Required for payments with
     * user-selected provider selection; optional for preselected provider selection.
     *
     * @return StartAuthorizationFlowRequestInterface
     */
    public function enableProviderSelection(): StartAuthorizationFlowRequestInterface;

    /**
     * Declares that your UI can render a scheme selection screen. Required for payments with
     * user-selected scheme selection; optional for other scheme selection types.
     *
     * @return StartAuthorizationFlowRequestInterface
     */
    public function enableSchemeSelection(): StartAuthorizationFlowRequestInterface;

    /**
     * Declares that your UI can render a user account selection screen.
     *
     * @return StartAuthorizationFlowRequestInterface
     */
    public function enableUserAccountSelection(): StartAuthorizationFlowRequestInterface;

    /**
     * The URI the end user is redirected to after they complete authorization on a third-party site, such
     * as their bank. Must be registered as an allowed redirect URI in the Console.
     *
     * @param string $returnUri
     *
     * @return StartAuthorizationFlowRequestInterface
     */
    public function returnUri(string $returnUri): StartAuthorizationFlowRequestInterface;

    /**
     * Only applicable if you're regulated and have a direct return URI registered with UK providers,
     * allowing the authorization flow to redirect directly from the provider to your page without going
     * via TrueLayer. `returnUri` should still be able to handle a non-direct return, in case the provider
     * doesn't support this.
     *
     * @param string $directReturnUri
     *
     * @return StartAuthorizationFlowRequestInterface
     */
    public function directReturnUri(string $directReturnUri): StartAuthorizationFlowRequestInterface;

    /**
     * Declares which form input types your UI supports, for providers that require additional inputs (such
     * as remitter name or account details) during authorization.
     *
     * @param string[] $types
     *
     * @return StartAuthorizationFlowRequestInterface
     */
    public function formInputTypes(array $types): StartAuthorizationFlowRequestInterface;

    /**
     * Send the request to start the authorization flow.
     *
     * @throws SignerException
     * @throws ApiResponseUnsuccessfulException
     * @throws InvalidArgumentException
     *
     * @return AuthorizationFlowResponseInterface
     */
    public function start(): AuthorizationFlowResponseInterface;
}
