<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payment\AuthorizationFlow;

use ArrayObject;
use TrueLayer\Constants\FormInputTypes;
use TrueLayer\Entities\Entity;
use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Exceptions\SignerException;
use TrueLayer\Exceptions\ValidationException;
use TrueLayer\Interfaces\HasApiFactoryInterface;
use TrueLayer\Interfaces\Payment\AuthorizationFlow\AuthorizationFlowResponseInterface;
use TrueLayer\Interfaces\Payment\StartAuthorizationFlowRequestInterface;
use TrueLayer\Traits\ProvidesApiFactory;
use TrueLayer\Validation\ValidType;

final class StartAuthorizationFlowRequest extends Entity implements StartAuthorizationFlowRequestInterface, HasApiFactoryInterface
{
    use ProvidesApiFactory;

    /**
     * @var string
     */
    protected string $paymentId;

    /**
     * @var ArrayObject<string, mixed>|null
     */
    protected ?ArrayObject $userAccountSelection;

    /**
     * @var ArrayObject<string, mixed>|null
     */
    protected ?ArrayObject $providerSelection;

    /**
     * @var ArrayObject<string, mixed>|null
     */
    protected ?ArrayObject $schemeSelection;

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
     * @return mixed[]
     */
    protected function rules(): array
    {
        return [
            'user_account_selection' => ['nullable', ValidType::of(ArrayObject::class)],
            'provider_selection' => ['nullable', ValidType::of(ArrayObject::class)],
            'scheme_selection' => ['nullable', ValidType::of(ArrayObject::class)],
            'redirect.return_uri' => ['required', 'string'],
            'redirect.direct_return_uri' => ['nullable', 'string'],
            'form.input_types' => ['nullable', 'array'],
            'form.input_types.*' => ['string'],
        ];
    }

    /**
     * @param string $paymentId
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
                FormInputTypes::SELECT
            ]);

        return $this;
    }

    /**
     * @return StartAuthorizationFlowRequestInterface
     */
    public function enableProviderSelection(): StartAuthorizationFlowRequestInterface
    {
        $this->providerSelection = new ArrayObject();
        return $this;
    }

    /**
     * @return StartAuthorizationFlowRequestInterface
     */
    public function enableSchemeSelection(): StartAuthorizationFlowRequestInterface
    {
        $this->schemeSelection = new ArrayObject();
        return $this;
    }

    /**
     * @return StartAuthorizationFlowRequestInterface
     */
    public function enableUserAccountSelection(): StartAuthorizationFlowRequestInterface
    {
        $this->userAccountSelection = new ArrayObject();
        return $this;
    }

    /**
     * @param string $returnUri
     * @return StartAuthorizationFlowRequestInterface
     */
    public function returnUri(string $returnUri): StartAuthorizationFlowRequestInterface
    {
        $this->returnUri = $returnUri;
        return $this;
    }

    /**
     * @param string $directReturnUri
     * @return StartAuthorizationFlowRequestInterface
     */
    public function directReturnUri(string $directReturnUri): StartAuthorizationFlowRequestInterface
    {
        $this->directReturnUri = $directReturnUri;
        return $this;
    }

    /**
     * @param string[] $types
     * @return StartAuthorizationFlowRequestInterface
     */
    public function formInputTypes(array $types): StartAuthorizationFlowRequestInterface
    {
        $this->formInputTypes = $types;
        return $this;
    }

    /**
     * @return AuthorizationFlowResponseInterface
     * @throws ApiRequestJsonSerializationException
     * @throws ApiResponseUnsuccessfulException
     * @throws InvalidArgumentException
     * @throws SignerException
     * @throws ValidationException
     */
    public function start(): AuthorizationFlowResponseInterface
    {
        $data = $this->getApiFactory()->paymentsApi()->startAuthorizationFlow(
            $this->paymentId,
            $this->validate()->toArray(),
        );

        return $this->make(AuthorizationFlowResponseInterface::class, $data);
    }
}
