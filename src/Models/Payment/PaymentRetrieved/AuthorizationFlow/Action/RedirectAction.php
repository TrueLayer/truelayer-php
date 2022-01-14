<?php

declare(strict_types=1);

namespace TrueLayer\Models\Payment\PaymentRetrieved\AuthorizationFlow\Action;

use TrueLayer\Constants\AuthorizationFlowActionTypes;
use TrueLayer\Contracts\Payment\AuthorizationFlow\Action\RedirectActionInterface;
use TrueLayer\Contracts\ProviderInterface;
use TrueLayer\Models\Payment\PaymentRetrieved\AuthorizationFlow\Action;
use TrueLayer\Models\Provider;
use TrueLayer\Validation\ValidType;

class RedirectAction extends Action implements RedirectActionInterface
{
    /**
     * @var string
     */
    protected string $uri;

    /**
     * @var Provider
     */
    protected ProviderInterface $provider;

    /**
     * @var string[]
     */
    protected array $arrayFields = [
        'type',
        'uri',
        'metadata' => 'provider',
    ];

    /**
     * @return mixed[]
     */
    protected function rules(): array
    {
        return [
            'uri' => 'required|url',
            'metadata' => ['nullable', ValidType::of(Provider::class)],
        ];
    }

    /**
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return AuthorizationFlowActionTypes::REDIRECT;
    }

    /**
     * @return string
     */
    public function getMetadataType(): string
    {
        return 'provider';
    }

    /**
     * @return ProviderInterface|null
     */
    public function getProvider(): ?ProviderInterface
    {
        return $this->provider ?? null;
    }
}
