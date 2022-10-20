<?php

declare(strict_types=1);

use Illuminate\Support\Str;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\HttpClient\Psr18Client;
use TrueLayer\Client;
use TrueLayer\Constants\Endpoints;
use TrueLayer\Exceptions\SignerException;
use TrueLayer\Interfaces\Client\ClientInterface;
use TrueLayer\Tests\Acceptance\Payment\CreatePayment;

$dotenv = new Dotenv();
$dotenv->load(__DIR__ . '/../../.env');

/**
 * @return ClientInterface
 * @throws SignerException
 *
 */
function client(): ClientInterface
{
    return Client::configure()
        ->clientId($_ENV['TEST_CLIENT_ID'])
        ->clientSecret($_ENV['TEST_CLIENT_SECRET'])
        ->keyId($_ENV['TEST_KID'])
        ->pemBase64($_ENV['TEST_PEM'])
        ->httpClient(new Psr18Client())
        ->create();
}

/**
 * @return CreatePayment
 * @throws SignerException
 *
 */
function paymentHelper(): CreatePayment
{
    return new CreatePayment(\client());
}

function bankAction(string $redirectUri, string $action): void
{
    $url = Str::before($redirectUri, '/login');
    $id = Str::after($redirectUri, 'login/');
    $id = Str::before($id, '#token');
    $token = Str::after($redirectUri, '#token=');
    
    $result = (new \GuzzleHttp\Client())->post("{$url}/api/single-immediate-payments/{$id}/action", [
        'headers' => [
            'authorization' => "Bearer {$token}",
        ],
        'json' => [
            'redirect' => false,
            'action' => $action,
        ],
    ]);

    $providerReturn = parse_url($result->getBody()->getContents());

    \client()->getApiClient()->request()
        ->uri(Endpoints::PAYMENTS_PROVIDER_RETURN)
        ->payload([
            'query' => "?" . $providerReturn['query'],
            'fragment' => "#" . $providerReturn['fragment']
        ])
        ->post();
}
