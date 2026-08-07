<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Payment;

/**
 * Configures an auto-constructed Hosted Payments Page URI, returned on the payment creation response.
 * Cannot be provided if starting the authorization flow explicitly.
 */
interface PaymentHostedPageInterface
{
    /**
     * @return string|null
     */
    public function getReturnUri(): ?string;

    /**
     * The URI where the user will be redirected after the authorization flow has completed on the hosted
     * page. Must be registered as an allowed redirect URI in the Console.
     *
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
     * The country code of the user, used to determine which banks to show on the hosted page initially.
     *
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
     * The language code of the user, used to determine which language to show on the hosted page,
     * overriding the browser's locale.
     *
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
     * The maximum time to wait for a result from the hosted page after the user has completed the
     * authorization flow.
     *
     * @param int $maxWaitForResult Seconds to wait for a result, between 0 and 60
     *
     * @return PaymentHostedPageInterface
     */
    public function maxWaitForResult(int $maxWaitForResult): PaymentHostedPageInterface;
}
