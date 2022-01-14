<?php

declare(strict_types=1);

namespace TrueLayer\Contracts\Payment;

use TrueLayer\Contracts\ArrayableInterface;
use TrueLayer\Contracts\HasAttributesInterface;
use TrueLayer\Contracts\Provider\ProviderFilterInterface;

interface PaymentMethodInterface extends ArrayableInterface, HasAttributesInterface
{
    /**
     * @param string $type
     *
     * @return PaymentMethodInterface
     */
    public function type(string $type): PaymentMethodInterface;

    /**
     * @param string $statementReference
     *
     * @return PaymentMethodInterface
     */
    public function statementReference(string $statementReference): PaymentMethodInterface;

    /**
     * @param ProviderFilterInterface $providerFilter
     *
     * @return PaymentMethodInterface
     */
    public function providerFilter(ProviderFilterInterface $providerFilter): PaymentMethodInterface;
}
