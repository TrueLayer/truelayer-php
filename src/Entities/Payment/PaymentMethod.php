<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payment;

use TrueLayer\Constants\PaymentMethods;
use TrueLayer\Interfaces\Payment\PaymentMethodInterface;
use TrueLayer\Interfaces\Provider\ProviderFilterInterface;
use TrueLayer\Entities\Entity;
use TrueLayer\Validation\AllowedConstant;
use TrueLayer\Validation\ValidType;

class PaymentMethod extends Entity implements PaymentMethodInterface
{
    /**
     * @var string
     */
    protected string $type;

    /**
     * @var string
     */
    protected string $statementReference;

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
        'statement_reference',
        'provider_filter',
    ];

    /**
     * @return mixed[]
     */
    protected function rules(): array
    {
        return [
            'type' => ['required', 'string', AllowedConstant::in(PaymentMethods::class)],
            'statement_reference' => ['string'],
            'provider_filter' => ['nullable', ValidType::of(ProviderFilterInterface::class)],
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
     * @param string $statementReference
     *
     * @return PaymentMethodInterface
     */
    public function statementReference(string $statementReference): PaymentMethodInterface
    {
        $this->statementReference = $statementReference;

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
}
