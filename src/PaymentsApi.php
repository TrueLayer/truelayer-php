<?php

declare(strict_types=1);

namespace TrueLayer;

use Http\Client\Common\HttpMethodsClient;
use Http\Client\Common\HttpMethodsClientInterface;
use Http\Discovery\Psr17FactoryDiscovery;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UriInterface;
use TrueLayer\Models\PaymentRequest;
use TrueLayer\Models\PaymentResponse;

final class PaymentsApi implements \TrueLayer\Contracts\Payments\PaymentsApi
{
    private const PRODUCTION_URL = "https://api.truelayer.com/payments";
    private const SANDBOX_URL = "https://test-pay-api.t7r.dev/payments";
    private HttpMethodsClientInterface $httpClient;
    private RequestFactoryInterface $requestFactory;
    private StreamFactoryInterface $streamFactory;
    private Options $options;

    public function __construct(
        \Http\Client\HttpClient $httpClient,
        Options $options,
        ?RequestFactoryInterface $requestFactory = null,
        ?StreamFactoryInterface $streamFactory = null
    ) {
        $this->requestFactory = $requestFactory ?? Psr17FactoryDiscovery::findRequestFactory();
        $this->streamFactory = $streamFactory ?? Psr17FactoryDiscovery::findStreamFactory();
        $this->httpClient = new HttpMethodsClient($httpClient, $this->requestFactory, $this->streamFactory);
        $this->options = $options;
    }

    public function createPayment(PaymentRequest $paymentRequest)
    {
        $response = $this->httpClient->post(
            ($this->options->useSandbox() ? self::SANDBOX_URL : self::PRODUCTION_URL),
            [],
            $this->createJsonBody($paymentRequest->toArray())
        );

        var_dump((string)$response->getBody());
    }

    public function getPayment(string $paymentId): PaymentResponse
    {
        $response = $this->httpClient->get(
            ($this->options->useSandbox() ? self::SANDBOX_URL : self::PRODUCTION_URL) . '/' . rawurlencode($paymentId),
        );

        return new PaymentResponse((string)$response->getBody());
    }

    public function createHostedPaymentPageLink(string $paymentId, string $resourceToken, UriInterface $returnUri): string
    {
        // TODO: Implement createHostedPaymentPageLink() method.
    }

    protected function createJsonBody(array $parameters): ?string
    {
        return (count($parameters) === 0) ? null : json_encode($parameters, empty($parameters) ? JSON_FORCE_OBJECT : 0);
    }
}
