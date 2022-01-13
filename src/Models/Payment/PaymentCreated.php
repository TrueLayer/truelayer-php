<?php

declare(strict_types=1);

namespace TrueLayer\Models\Payment;

use TrueLayer\Contracts\HppInterface;
use TrueLayer\Contracts\Payment\PaymentCreatedInterface;
use TrueLayer\Contracts\Payment\PaymentRetrievedInterface;
use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\SignerException;
use TrueLayer\Exceptions\ValidationException;
use TrueLayer\Models\Model;

final class PaymentCreated extends Model implements PaymentCreatedInterface
{
    /**
     * @var string
     */
    protected string $id;

    /**
     * @var string
     */
    protected string $paymentToken;

    /**
     * @var string
     */
    protected string $userId;

    /**
     * @var string[]
     */
    protected array $arrayFields = [
        'id',
        'payment_token',
        'user.id' => 'user_id',
    ];

    /**
     * @var string[]
     */
    protected array $rules = [
        'id' => 'required|string',
        'user.id' => 'required|string',
        'payment_token' => 'required|string',
    ];

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getPaymentToken(): string
    {
        return $this->paymentToken;
    }

    /**
     * @return string
     */
    public function getUserId(): string
    {
        return $this->userId;
    }

    /**
     * @return HppInterface
     */
    public function hostedPaymentsPage(): HppInterface
    {
        return $this->getSdk()
            ->hostedPaymentsPage()
            ->paymentId($this->getId())
            ->paymentToken($this->getPaymentToken());
    }

    /**
     * @throws ValidationException
     * @throws ApiRequestJsonSerializationException
     * @throws SignerException
     * @throws ApiResponseUnsuccessfulException
     *
     * @return PaymentRetrievedInterface
     */
    public function getDetails(): PaymentRetrievedInterface
    {
        return $this->getSdk()->getPaymentDetails(
            $this->getId()
        );
    }
}
