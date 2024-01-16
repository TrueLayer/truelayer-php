<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payment;

use TrueLayer\Entities\Entity;
use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Exceptions\SignerException;
use TrueLayer\Exceptions\ValidationException;
use TrueLayer\Interfaces\HasApiFactoryInterface;
use TrueLayer\Interfaces\Payment\PaymentCreatedInterface;
use TrueLayer\Interfaces\Payment\PaymentRequestInterface;
use TrueLayer\Interfaces\PaymentMethod\PaymentMethodInterface;
use TrueLayer\Interfaces\RequestOptionsInterface;
use TrueLayer\Interfaces\UserInterface;
use TrueLayer\Traits\ProvidesApiFactory;
use TrueLayer\Validation\ValidType;

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
     * @var RequestOptionsInterface|null
     */
    protected ?RequestOptionsInterface $requestOptions = null;

    /**
     * @var string[]
     */
    protected array $casts = [
        'payment_method' => PaymentMethodInterface::class,
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
        'user',
    ];

    /**
     * @return mixed[]
     */
    protected function rules(): array
    {
        return [
            'amount_in_minor' => 'required|int|min:1',
            'currency' => ['required', 'string'],
            'metadata' => 'nullable|array',
            'payment_method' => ['required', ValidType::of(PaymentMethodInterface::class)],
            'user' => ['required', ValidType::of(UserInterface::class)],
        ];
    }

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
     * @throws ApiRequestJsonSerializationException
     * @throws ApiResponseUnsuccessfulException
     * @throws InvalidArgumentException
     * @throws ValidationException
     * @throws SignerException
     *
     * @return PaymentCreatedInterface
     */
    public function create(): PaymentCreatedInterface
    {
        $data = $this->getApiFactory()->paymentsApi()->create(
            $this->validate()->toArray(),
            $this->requestOptions
        );

        return $this->make(PaymentCreatedInterface::class, $data);
    }
}
