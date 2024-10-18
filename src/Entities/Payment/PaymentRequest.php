<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payment;

use TrueLayer\Attributes\Field;
use TrueLayer\Entities\Entity;
use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Exceptions\SignerException;
use TrueLayer\Interfaces\HasApiFactoryInterface;
use TrueLayer\Interfaces\Payment\PaymentCreatedInterface;
use TrueLayer\Interfaces\Payment\PaymentRequestInterface;
use TrueLayer\Interfaces\Payment\PaymentRiskAssessmentInterface;
use TrueLayer\Interfaces\PaymentMethod\PaymentMethodInterface;
use TrueLayer\Interfaces\RequestOptionsInterface;
use TrueLayer\Interfaces\UserInterface;
use TrueLayer\Traits\ProvidesApiFactory;

final class PaymentRequest extends Entity implements PaymentRequestInterface, HasApiFactoryInterface
{
    use ProvidesApiFactory;

    /**
     * @var int
     */
    #[Field]
    protected int $amountInMinor;

    /**
     * @var string
     */
    #[Field]
    protected string $currency;

    /**
     * @var PaymentMethodInterface
     */
    #[Field]
    protected PaymentMethodInterface $paymentMethod;

    /**
     * @var UserInterface
     */
    #[Field]
    protected UserInterface $user;

    /**
     * @var array<string, string>
     */
    #[Field]
    protected array $metadata;

    /**
     * @var PaymentRiskAssessmentInterface
     */
    #[Field]
    protected PaymentRiskAssessmentInterface $riskAssessment;

    /**
     * @var RequestOptionsInterface|null
     */
    protected ?RequestOptionsInterface $requestOptions = null;

    /**
     * @param int $amount
     *
     * @return PaymentRequestInterface
     */
    public function amountInMinor(int $amount): PaymentRequestInterface
    {
        $this->amountInMinor = $amount;

        return $this;
    }

    /**
     * @param string $currency
     *
     * @return PaymentRequestInterface
     */
    public function currency(string $currency): PaymentRequestInterface
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * @param array<string, string> $metadata
     *
     * @return PaymentRequestInterface
     */
    public function metadata(array $metadata): PaymentRequestInterface
    {
        $this->metadata = $metadata;

        return $this;
    }

    /**
     * @param PaymentMethodInterface $paymentMethod
     *
     * @return PaymentRequestInterface
     */
    public function paymentMethod(PaymentMethodInterface $paymentMethod): PaymentRequestInterface
    {
        $this->paymentMethod = $paymentMethod;

        return $this;
    }

    /**
     * @param PaymentRiskAssessmentInterface|null $riskAssessment
     *
     * @return PaymentRiskAssessmentInterface
     * @throws InvalidArgumentException
     *
     */
    public function riskAssessment(?PaymentRiskAssessmentInterface $riskAssessment = null): PaymentRiskAssessmentInterface
    {
        $this->riskAssessment = $riskAssessment ?: $this->entityFactory->make(PaymentRiskAssessmentInterface::class);

        return $this->riskAssessment;
    }

    /**
     * @param UserInterface $user
     *
     * @return PaymentRequestInterface
     */
    public function user(UserInterface $user): PaymentRequestInterface
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @param RequestOptionsInterface $requestOptions
     *
     * @return $this
     */
    public function requestOptions(RequestOptionsInterface $requestOptions): PaymentRequestInterface
    {
        $this->requestOptions = $requestOptions;

        return $this;
    }

    /**
     * @return PaymentCreatedInterface
     * @throws InvalidArgumentException
     * @throws SignerException
     * @throws ApiRequestJsonSerializationException
     *
     * @throws ApiResponseUnsuccessfulException
     */
    public function create(): PaymentCreatedInterface
    {
        $data = $this->getApiFactory()->paymentsApi()->create(
            $this->toArray(),
            $this->requestOptions
        );

        return $this->make(PaymentCreatedInterface::class, $data);
    }
}
