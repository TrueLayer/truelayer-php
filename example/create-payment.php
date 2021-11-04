<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$options = new \TrueLayer\Options(
    'test-53ac4e',
    file_get_contents(__DIR__ . '/client-secret.txt'),
    'e6e4f604-6905-46e8-83d3-f588fc1d1e6a',
    file_get_contents(__DIR__ . '/private-key.pem'),
    true
);
$httpClient = \TrueLayer\HttpClientFactory::create($options);

$auth = new \TrueLayer\Authentication\AuthApi($httpClient, $options);

$accessToken = $auth->getAuthToken();
var_dump($accessToken->getToken());

$paymentsHttpClient = \TrueLayer\HttpClientFactory::create($options, $accessToken, true);
$payments = new \TrueLayer\PaymentsApi($paymentsHttpClient, $options);

$paymentRequest = new \TrueLayer\Models\PaymentRequest(
    1,
    \TrueLayer\Constants\Currencies::GBP,
    new \TrueLayer\Models\Beneficiary('external', 'Test ref', 'Benny Fishery', '04-00-04', '12345678')
);

$payments->createPayment($paymentRequest);
