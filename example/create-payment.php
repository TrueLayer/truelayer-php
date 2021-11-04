<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$options = new \TrueLayer\Options(
    'sandbox-securemeabeer-17cd55',
    file_get_contents(__DIR__ . '/client-secret.txt'),
    'f9f60900-f832-4c34-b4be-3049eecfed45',
    file_get_contents(__DIR__ . '/private-key.pem'),
    true
);
$httpClient = \TrueLayer\HttpClientFactory::create($options);

$auth = new \TrueLayer\Authentication\AuthApi($httpClient, $options);

$accessToken = $auth->getAuthToken();

$paymentsHttpClient = \TrueLayer\HttpClientFactory::create($options, $accessToken, true);
$payments = new \TrueLayer\PaymentsApi($paymentsHttpClient, $options);

$paymentRequest = new \TrueLayer\Models\PaymentRequest(
    1,
    \TrueLayer\Constants\Currencies::GBP,
    new \TrueLayer\Models\Beneficiary('external', 'Test ref', 'Benny Fishery', '04-00-04', '12345678')
);

$payments->createPayment($paymentRequest);
