<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payout;

use TrueLayer\Entities\Entity;
use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Exceptions\SignerException;
use TrueLayer\Interfaces\HasApiFactoryInterface;
use TrueLayer\Interfaces\Payout\Beneficiary\BeneficiaryInterface;
use TrueLayer\Interfaces\Payout\PayoutCreatedInterface;
use TrueLayer\Interfaces\Payout\PayoutRequestInterface;
use TrueLayer\Interfaces\Payout\Scheme\SchemeSelectionInterface;
use TrueLayer\Interfaces\RequestOptionsInterface;
use TrueLayer\Traits\ProvidesApiFactory;

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
     * @var BeneficiaryInterface
     */
    protected BeneficiaryInterface $beneficiary;

    /**
     * @var SchemeSelectionInterface
     */
    protected SchemeSelectionInterface $schemeSelection;

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
        'beneficiary' => BeneficiaryInterface::class,
        'scheme_selection' => SchemeSelectionInterface::class,
    ];

    /**
     * @var string[]
     */
    protected array $arrayFields = [
        'merchant_account_id',
        'amount_in_minor',
        'currency',
        'beneficiary',
        'scheme_selection',
        'metadata',
    ];

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
     * @param BeneficiaryInterface $beneficiary
     *
     * @return PayoutRequestInterface
     */
    public function beneficiary(BeneficiaryInterface $beneficiary): PayoutRequestInterface
    {
        $this->beneficiary = $beneficiary;

        return $this;
    }

    /**
     * @param SchemeSelectionInterface $schemeSelection
     *
     * @return PayoutRequestInterface
     */
    public function schemeSelection(SchemeSelectionInterface $schemeSelection): PayoutRequestInterface
    {
        $this->schemeSelection = $schemeSelection;

        return $this;
    }

    /**
     * @param array<string, string> $metadata
     *
     * @return PayoutRequestInterface
     */
    public function metadata(array $metadata): PayoutRequestInterface
    {
        if (!empty($metadata)) {
            $this->metadata = $metadata;
        }

        return $this;
    }

    /**
     * @param RequestOptionsInterface $requestOptions
     *
     * @return $this
     */
    public function requestOptions(RequestOptionsInterface $requestOptions): PayoutRequestInterface
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
     * @return PayoutCreatedInterface
     */
    public function create(): PayoutCreatedInterface
    {
        $data = $this->getApiFactory()->payoutsApi()->create(
            $this->toArray(),
            $this->requestOptions
        );

        return $this->make(PayoutCreatedInterface::class, $data);
    }
}
