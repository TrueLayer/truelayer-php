<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payment;

use TrueLayer\Constants\PaymentMethods;
use TrueLayer\Entities\Entity;
use TrueLayer\Interfaces\Payment\PaymentMethodInterface;
use TrueLayer\Interfaces\Provider\ProviderFilterInterface;
use TrueLayer\Validation\AllowedConstant;
use TrueLayer\Validation\ValidType;

class PaymentMethod extends Entity implements PaymentMethodInterface
{
    /**
     * @var string
     */
    protected string $type;

    /**
     * @var ProviderFilterInterface
     */
    protected ProviderFilterInterface $providerFilter;

    /**
     * @var string[]
     */
    protected array $casts = [
        'provider_filter' => ProviderFilterInterface::class,
    ];

    /**
     * @var string[]
     */
    protected array $arrayFields = [
        'type',
        'provider.filter',
        'provider.type' => 'provider_type',
    ];

    /**
     * @return mixed[]
     */
    protected function rules(): array
    {
        return [
            'type' => ['required', 'string', AllowedConstant::in(PaymentMethods::class)],
            'statement_reference' => ['string'],
            'provider.filter' => ['nullable', ValidType::of(ProviderFilterInterface::class)],
        ];
    }

    /**
     * @param string $type
     *
     * @return PaymentMethodInterface
     */
    public function type(string $type): PaymentMethodInterface
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @param ProviderFilterInterface $providerFilter
     *
     * @return PaymentMethodInterface
     */
    public function providerFilter(ProviderFilterInterface $providerFilter): PaymentMethodInterface
    {
        $this->providerFilter = $providerFilter;

        return $this;
    }

    /**
     * @return string
     */
    public function getProviderType(): string
    {
        return PaymentMethods::PROVIDER_TYPE_USER_SELECTION;
    }
}
