<?php

declare(strict_types=1);

namespace TrueLayer\Builders;

use TrueLayer\Constants\Endpoints;
use TrueLayer\Contracts\Builders\PaymentRequestBuilderInterface;
use TrueLayer\Contracts\Services\HttpClientInterface;
use TrueLayer\Models\Payment;

class PaymentRequestBuilder extends Payment implements PaymentRequestBuilderInterface
{
    /**
     * @var HttpClientInterface
     */
    private HttpClientInterface $httpClient;

    /**
     * @param HttpClientInterface $httpClient
     */
    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function create()
    {
        var_dump($this->toArray());

        try {
            $response = $this->httpClient
                ->withSignature()
                ->withAuthToken()
                ->post(Endpoints::PAYMENTS, $this->toArray());
        } catch (\Exception $e) {
            var_dump($e->getResponse()->getBody()->getContents());
        }
        var_dump(json_decode($response->getBody()->getContents())); die();
    }
}
