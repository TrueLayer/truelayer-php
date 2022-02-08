<?php

declare(strict_types=1);

use Illuminate\Support\Str;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\HttpClient\Psr18Client;
use TrueLayer\Exceptions\SignerException;
use TrueLayer\Interfaces\Sdk\SdkInterface;
use TrueLayer\Sdk;
use TrueLayer\Tests\Acceptance\Payment\CreatePayment;

$dotenv = new Dotenv();
$dotenv->load(__DIR__ . '/../../.env');

/**
 * @throws SignerException
 *
 * @return SdkInterface
 */
function sdk(): SdkInterface
{
    return Sdk::configure()
        ->clientId($_ENV['TEST_CLIENT_ID'])
        ->clientSecret($_ENV['TEST_CLIENT_SECRET'])
        ->keyId($_ENV['TEST_KID'])
        ->pemBase64($_ENV['TEST_PEM'])
        ->httpClient(new Psr18Client())
        ->create();
}

/**
 * @throws SignerException
 *
 * @return CreatePayment
 */
function paymentHelper(): CreatePayment
{
    return new CreatePayment(\sdk());
}

function bankAction(string $redirectUri, string $action): void
{
    $url = Str::before($redirectUri, '/login');
    $id = Str::after($redirectUri, 'login/');
    $id = Str::before($id, '#token');
    $token = Str::after($redirectUri, '#token=');

    (new \GuzzleHttp\Client())->post("{$url}/api/single-immediate-payments/{$id}/action", [
        'headers' => [
            'authorization' => "Bearer {$token}",
        ],
        'json' => [
            'redirect' => false,
            'action' => $action,
        ],
    ]);
}
