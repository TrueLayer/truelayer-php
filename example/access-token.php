<?php

require __DIR__ . '/../vendor/autoload.php';

$options = new \TrueLayer\Options('', '');
$httpClient = \TrueLayer\HttpClientFactory::create();

$auth = new \TrueLayer\Authentication\AuthApi($httpClient, $options);

$accessToken = $auth->getAuthToken();

var_dump($accessToken->getToken());
