<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payment\Refund;

use TrueLayer\Entities\Entity;
use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Exceptions\SignerException;
use TrueLayer\Interfaces\HasApiFactoryInterface;
use TrueLayer\Interfaces\Payment\PaymentCreatedInterface;
use TrueLayer\Interfaces\Payment\PaymentRetrievedInterface;
use TrueLayer\Interfaces\Payment\RefundCreatedInterface;
use TrueLayer\Interfaces\Payment\RefundRequestInterface;
use TrueLayer\Interfaces\RequestOptionsInterface;
use TrueLayer\Services\Util\PaymentId;
use TrueLayer\Traits\ProvidesApiFactory;

final class RefundRequest extends Entity implements RefundRequestInterface, HasApiFactoryInterface
{
    use ProvidesApiFactory;

    /**
     * @var string
     */
    protected string $paymentId;

    /**
     * @var int
     */
    protected int $amountInMinor;

    /**
     * @var string
     */
    protected string $reference;

    /**
     * @var RequestOptionsInterface|null
     */
    protected ?RequestOptionsInterface $requestOptions = null;

    /**
     * @var string[]
     */
    protected array $arrayFields = [
        'payment_id',
        'amount_in_minor',
        'reference',
    ];

    /**
     * @param string|PaymentRetrievedInterface|PaymentCreatedInterface $payment
     *
     * @throws InvalidArgumentException
     *
     * @return RefundRequestInterface
     */
    public function payment($payment): RefundRequestInterface
    {
        $this->paymentId = PaymentId::find($payment);

        return $this;
    }

    /**
     * @param int $amount
     *
     * @return RefundRequestInterface
     */
    public function amountInMinor(int $amount): RefundRequestInterface
    {
        $this->amountInMinor = $amount;

        return $this;
    }

    /**
     * @param string $reference
     *
     * @return RefundRequestInterface
     */
    public function reference(string $reference): RefundRequestInterface
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * @param RequestOptionsInterface $requestOptions
     *
     * @return RefundRequestInterface
     */
    public function requestOptions(RequestOptionsInterface $requestOptions): RefundRequestInterface
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
     * @return RefundCreatedInterface
     */
    public function create(): RefundCreatedInterface
    {
        $data = $this->getApiFactory()->paymentsApi()->createRefund(
            $this->paymentId,
            $this->toArray(),
            $this->requestOptions
        );

        return $this->make(RefundCreatedInterface::class, $data);
    }
}
