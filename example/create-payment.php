<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$options = new \TrueLayer\Options(
    'test-390ed6',
    file_get_contents(__DIR__ . '/client-secret.txt'),
    '8aa1e9dc-49b6-4afd-9152-5af9e0b9db47',
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

$payment = $payments->getPayment('a005f886-18a7-46ef-af89-ebff400c142c');
var_dump($payment);
