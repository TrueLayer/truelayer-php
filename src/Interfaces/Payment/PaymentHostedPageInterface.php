<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Payment;

interface PaymentHostedPageInterface
{
    /**
     * @return string|null
     */
    public function getReturnUri(): ?string;

    /**
     * @param string $returnUri
     *
     * @return PaymentHostedPageInterface
     */
    public function returnUri(string $returnUri): PaymentHostedPageInterface;

    /**
     * @return string|null
     */
    public function getCountryCode(): ?string;

    /**
     * @param string $countryCode ISO 3166-1 alpha-2 country code
     *
     * @return PaymentHostedPageInterface
     */
    public function countryCode(string $countryCode): PaymentHostedPageInterface;

    /**
     * @return string|null
     */
    public function getLanguageCode(): ?string;

    /**
     * @param string $languageCode ISO 639-1 language code
     *
     * @return PaymentHostedPageInterface
     */
    public function languageCode(string $languageCode): PaymentHostedPageInterface;

    /**
     * @return int|null
     */
    public function getMaxWaitForResult(): ?int;

    /**
     * @param int $maxWaitForResult Seconds to wait for a result, between 0 and 60
     *
     * @return PaymentHostedPageInterface
     */
    public function maxWaitForResult(int $maxWaitForResult): PaymentHostedPageInterface;
}
