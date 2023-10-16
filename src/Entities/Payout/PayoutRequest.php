<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payout;

use TrueLayer\Entities\Entity;
use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Exceptions\SignerException;
use TrueLayer\Exceptions\ValidationException;
use TrueLayer\Interfaces\Beneficiary\BeneficiaryInterface;
use TrueLayer\Interfaces\Beneficiary\ExternalAccountBeneficiaryInterface;
use TrueLayer\Interfaces\HasApiFactoryInterface;
use TrueLayer\Interfaces\Payout\PayoutBeneficiaryInterface;
use TrueLayer\Interfaces\Payout\PayoutCreatedInterface;
use TrueLayer\Interfaces\Payout\PayoutRequestInterface;
use TrueLayer\Interfaces\RequestOptionsInterface;
use TrueLayer\Traits\ProvidesApiFactory;
use TrueLayer\Validation\ValidType;

final class PayoutRequest extends Entity implements PayoutRequestInterface, HasApiFactoryInterface
{
    use ProvidesApiFactory;

    /**
     * @var string
     */
    protected string $merchantAccountId;

    /**
     * @var int
     */
    protected int $amountInMinor;

    /**
     * @var string
     */
    protected string $currency;

    /**
     * @var PayoutBeneficiaryInterface
     */
    protected PayoutBeneficiaryInterface $beneficiary;

    /**
     * @var RequestOptionsInterface|null
     */
    protected ?RequestOptionsInterface $requestOptions = null;

    /**
     * @var string[]
     */
    protected array $casts = [
        'beneficiary' => BeneficiaryInterface::class,
    ];

    /**
     * @var string[]
     */
    protected array $arrayFields = [
        'merchant_account_id',
        'amount_in_minor',
        'currency',
        'beneficiary',
    ];

    /**
     * @return mixed[]
     */
    protected function rules(): array
    {
        return [
            'merchant_account_id' => 'required|string',
            'amount_in_minor' => 'required|int|min:1',
            'currency' => 'required|string',
            'beneficiary' => ['required', ValidType::of(PayoutBeneficiaryInterface::class)],
        ];
    }

    /**
     * @param int $amount
     *
     * @return PayoutRequestInterface
     */
    public function amountInMinor(int $amount): PayoutRequestInterface
    {
        $this->amountInMinor = $amount;

        return $this;
    }

    /**
     * @param string $currency
     *
     * @return PayoutRequestInterface
     */
    public function currency(string $currency): PayoutRequestInterface
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * @param string $merchantAccountId
     *
     * @return PayoutRequestInterface
     */
    public function merchantAccountId(string $merchantAccountId): PayoutRequestInterface
    {
        $this->merchantAccountId = $merchantAccountId;

        return $this;
    }

    /**
     * @param ExternalAccountBeneficiaryInterface $beneficiary
     *
     * @return PayoutRequestInterface
     */
    public function beneficiary(PayoutBeneficiaryInterface $beneficiary): PayoutRequestInterface
    {
        $this->beneficiary = $beneficiary;

        return $this;
    }

    /**
     * @param RequestOptionsInterface $requestOptions
     * @return $this
     */
    public function requestOptions(RequestOptionsInterface $requestOptions): PayoutRequestInterface
    {
        $this->requestOptions = $requestOptions;

        return $this;
    }

    /**
     * @return PayoutCreatedInterface
     * @throws SignerException
     * @throws ApiRequestJsonSerializationException
     * @throws ApiResponseUnsuccessfulException
     * @throws InvalidArgumentException
     *
     * @throws ValidationException
     */
    public function create(): PayoutCreatedInterface
    {
        $data = $this->getApiFactory()->payoutsApi()->create(
            $this->validate()->toArray(),
            $this->requestOptions
        );

        return $this->make(PayoutCreatedInterface::class, $data);
    }
}
