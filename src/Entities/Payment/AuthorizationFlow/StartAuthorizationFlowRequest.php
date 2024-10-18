<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payment\AuthorizationFlow;

use TrueLayer\Attributes\Field;
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
    #[Field]
    protected ?\ArrayObject $userAccountSelection;

    /**
     * @var \ArrayObject<string, mixed>|null
     */
    #[Field]
    protected ?\ArrayObject $providerSelection;

    /**
     * @var \ArrayObject<string, mixed>|null
     */
    #[Field]
    protected ?\ArrayObject $schemeSelection;

    /**
     * @var string
     */
    #[Field('redirect.direct_return_uri')]
    protected string $directReturnUri;

    /**
     * @var string
     */
    #[Field('redirect.return_uri')]
    protected string $returnUri;

    /**
     * @var string[]
     */
    #[Field('form.input_types')]
    protected array $formInputTypes = [];

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
     * @throws InvalidArgumentException
     * @throws SignerException
     * @throws ApiRequestJsonSerializationException
     *
     * @throws ApiResponseUnsuccessfulException
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
