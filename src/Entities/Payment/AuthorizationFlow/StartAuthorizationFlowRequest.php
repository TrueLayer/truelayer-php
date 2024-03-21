<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payment\AuthorizationFlow;

use TrueLayer\Constants\FormInputTypes;
use TrueLayer\Entities\Entity;
use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Exceptions\SignerException;
use TrueLayer\Interfaces\HasApiFactoryInterface;
use TrueLayer\Interfaces\Payment\AuthorizationFlow\AuthorizationFlowResponseInterface;
use TrueLayer\Interfaces\Payment\StartAuthorizationFlowRequestInterface;
use TrueLayer\Traits\ProvidesApiFactory;

final class StartAuthorizationFlowRequest extends Entity implements StartAuthorizationFlowRequestInterface, HasApiFactoryInterface
{
    use ProvidesApiFactory;

    /**
     * @var string
     */
    protected string $paymentId;

    /**
     * @var \ArrayObject<string, mixed>|null
     */
    protected ?\ArrayObject $userAccountSelection;

    /**
     * @var \ArrayObject<string, mixed>|null
     */
    protected ?\ArrayObject $providerSelection;

    /**
     * @var \ArrayObject<string, mixed>|null
     */
    protected ?\ArrayObject $schemeSelection;

    /**
     * @var string
     */
    protected string $directReturnUri;

    /**
     * @var string
     */
    protected string $returnUri;

    /**
     * @var string[]
     */
    protected array $formInputTypes = [];

    protected array $arrayFields = [
        'user_account_selection',
        'provider_selection',
        'scheme_selection',
        'redirect.return_uri' => 'returnUri',
        'redirect.direct_return_uri' => 'directReturnUri',
        'form.input_types' => 'formInputTypes',
    ];

    /**
     * @param string $paymentId
     *
     * @return StartAuthorizationFlowRequestInterface
     */
    public function paymentId(string $paymentId): StartAuthorizationFlowRequestInterface
    {
        $this->paymentId = $paymentId;

        return $this;
    }

    /**
     * @return StartAuthorizationFlowRequestInterface
     */
    public function useHPPCapabilities(): StartAuthorizationFlowRequestInterface
    {
        $this->enableProviderSelection()
            ->enableSchemeSelection()
            ->formInputTypes([
                FormInputTypes::TEXT,
                FormInputTypes::TEXT_WITH_IMAGE,
                FormInputTypes::SELECT,
            ]);

        return $this;
    }

    /**
     * @return StartAuthorizationFlowRequestInterface
     */
    public function enableProviderSelection(): StartAuthorizationFlowRequestInterface
    {
        $this->providerSelection = new \ArrayObject();

        return $this;
    }

    /**
     * @return StartAuthorizationFlowRequestInterface
     */
    public function enableSchemeSelection(): StartAuthorizationFlowRequestInterface
    {
        $this->schemeSelection = new \ArrayObject();

        return $this;
    }

    /**
     * @return StartAuthorizationFlowRequestInterface
     */
    public function enableUserAccountSelection(): StartAuthorizationFlowRequestInterface
    {
        $this->userAccountSelection = new \ArrayObject();

        return $this;
    }

    /**
     * @param string $returnUri
     *
     * @return StartAuthorizationFlowRequestInterface
     */
    public function returnUri(string $returnUri): StartAuthorizationFlowRequestInterface
    {
        $this->returnUri = $returnUri;

        return $this;
    }

    /**
     * @param string $directReturnUri
     *
     * @return StartAuthorizationFlowRequestInterface
     */
    public function directReturnUri(string $directReturnUri): StartAuthorizationFlowRequestInterface
    {
        $this->directReturnUri = $directReturnUri;

        return $this;
    }

    /**
     * @param string[] $types
     *
     * @return StartAuthorizationFlowRequestInterface
     */
    public function formInputTypes(array $types): StartAuthorizationFlowRequestInterface
    {
        $this->formInputTypes = $types;

        return $this;
    }

    /**
     * @return AuthorizationFlowResponseInterface
     * @throws ApiResponseUnsuccessfulException
     * @throws InvalidArgumentException
     * @throws SignerException
     *
     * @throws ApiRequestJsonSerializationException
     */
    public function start(): AuthorizationFlowResponseInterface
    {
        $data = $this->getApiFactory()->paymentsApi()->startAuthorizationFlow(
            $this->paymentId,
            $this->toArray(),
        );

        return $this->make(AuthorizationFlowResponseInterface::class, $data);
    }
}
