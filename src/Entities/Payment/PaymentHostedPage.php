<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payment;

use TrueLayer\Entities\Entity;
use TrueLayer\Interfaces\Payment\PaymentHostedPageInterface;

class PaymentHostedPage extends Entity implements PaymentHostedPageInterface
{
    /**
     * @var string
     */
    protected string $returnUri;

    /**
     * @var string
     */
    protected string $countryCode;

    /**
     * @var string
     */
    protected string $languageCode;

    /**
     * @var int
     */
    protected int $maxWaitForResult;

    /**
     * @var string[]
     */
    protected array $arrayFields = [
        'return_uri',
        'country_code',
        'language_code',
        'max_wait_for_result',
    ];

    /**
     * @return string|null
     */
    public function getReturnUri(): ?string
    {
        return $this->returnUri ?? null;
    }

    /**
     * @param string $returnUri
     *
     * @return PaymentHostedPageInterface
     */
    public function returnUri(string $returnUri): PaymentHostedPageInterface
    {
        $this->returnUri = $returnUri;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCountryCode(): ?string
    {
        return $this->countryCode ?? null;
    }

    /**
     * @param string $countryCode
     *
     * @return PaymentHostedPageInterface
     */
    public function countryCode(string $countryCode): PaymentHostedPageInterface
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLanguageCode(): ?string
    {
        return $this->languageCode ?? null;
    }

    /**
     * @param string $languageCode
     *
     * @return PaymentHostedPageInterface
     */
    public function languageCode(string $languageCode): PaymentHostedPageInterface
    {
        $this->languageCode = $languageCode;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getMaxWaitForResult(): ?int
    {
        return $this->maxWaitForResult ?? null;
    }

    /**
     * @param int $maxWaitForResult
     *
     * @return PaymentHostedPageInterface
     */
    public function maxWaitForResult(int $maxWaitForResult): PaymentHostedPageInterface
    {
        $this->maxWaitForResult = $maxWaitForResult;

        return $this;
    }
}
