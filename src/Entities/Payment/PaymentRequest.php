<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payment;

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
use TrueLayer\Interfaces\SubMerchant\PaymentSubMerchantsInterface;
use TrueLayer\Interfaces\UserInterface;
use TrueLayer\Traits\ProvidesApiFactory;

final class PaymentRequest extends Entity implements PaymentRequestInterface, HasApiFactoryInterface
{
    use ProvidesApiFactory;

    /**
     * @var int
     */
    protected int $amountInMinor;

    /**
     * @var string
     */
    protected string $currency;

    /**
     * @var PaymentMethodInterface
     */
    protected PaymentMethodInterface $paymentMethod;

    /**
     * @var UserInterface
     */
    protected UserInterface $user;

    /**
     * @var array<string, string>
     */
    protected array $metadata;

    /**
     * @var PaymentRiskAssessmentInterface
     */
    protected PaymentRiskAssessmentInterface $riskAssessment;

    /**
     * @var PaymentSubMerchantsInterface
     */
    protected PaymentSubMerchantsInterface $subMerchants;

    /**
     * @var RequestOptionsInterface|null
     */
    protected ?RequestOptionsInterface $requestOptions = null;

    /**
     * @var string[]
     */
    protected array $casts = [
        'payment_method' => PaymentMethodInterface::class,
        'risk_assessment' => PaymentRiskAssessmentInterface::class,
        'sub_merchants' => PaymentSubMerchantsInterface::class,
        'user' => UserInterface::class,
    ];

    /**
     * @var string[]
     */
    protected array $arrayFields = [
        'amount_in_minor',
        'currency',
        'metadata',
        'payment_method',
        'risk_assessment',
        'sub_merchants',
        'user',
    ];

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
        if (!empty($metadata)) {
            $this->metadata = $metadata;
        }

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
     * @throws InvalidArgumentException
     *
     * @return PaymentRiskAssessmentInterface
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
     * @param PaymentSubMerchantsInterface|null $subMerchants
     *
     * @throws InvalidArgumentException
     *
     * @return PaymentSubMerchantsInterface
     */
    public function subMerchants(?PaymentSubMerchantsInterface $subMerchants = null): PaymentSubMerchantsInterface
    {
        $this->subMerchants = $subMerchants ?: $this->entityFactory->make(PaymentSubMerchantsInterface::class);

        return $this->subMerchants;
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
     * @throws ApiResponseUnsuccessfulException
     * @throws InvalidArgumentException
     * @throws SignerException
     * @throws ApiRequestJsonSerializationException
     *
     * @return PaymentCreatedInterface
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
