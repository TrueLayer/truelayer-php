<?php

declare(strict_types=1);

use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Carbon;
use Jose\Component\KeyManagement\JWKFactory;
use TrueLayer\Constants\Encryption;
use TrueLayer\Exceptions\WebhookVerificationFailedException;
use TrueLayer\Services\Util\Encryption\Encrypter;
use TrueLayer\Tests\Integration\Mocks\Signing;
use TrueLayer\Tests\Integration\Mocks\WebhookPayload;

\it('uses keys from cache', function () {
    $key = '31c8d81a110849f83131541b9f67c3cba9c7e0bb103bc4dd19377f0fdf2d924b';
    $encrypter = new Encrypter(\hex2bin($key), Encryption::ALGORITHM);

    $cacheData = \json_decode(Signing::getPublicKeysResponse()->getBody()->getContents(), true);
    $cacheData['retrieved_at'] = Carbon::now()->timestamp;
    $cacheData = $encrypter->encrypt($cacheData);

    $cache = Mockery::mock(\Psr\SimpleCache\CacheInterface::class);
    $cache->shouldReceive('has')->andReturnTrue();
    $cache->shouldReceive('set')->andReturnTrue();
    $cache->shouldReceive('get')->andReturn($cacheData);

    $body = WebhookPayload::paymentExecuted();

    \rawClient()
        ->cache($cache, '31c8d81a110849f83131541b9f67c3cba9c7e0bb103bc4dd19377f0fdf2d924b')
        ->create()
        ->webhook()
        ->body($body)
        ->path(Signing::getPath())
        ->headers(Signing::getHeaders($body))
        ->execute();

    // No requests were sent to fetch the keys, because the cached version was used
    \expect(\getSentHttpRequests())->toHaveLength(0);
});

\it('refetches keys on expiry', function () {
    $key = '31c8d81a110849f83131541b9f67c3cba9c7e0bb103bc4dd19377f0fdf2d924b';
    $encrypter = new Encrypter(\hex2bin($key), Encryption::ALGORITHM);

    $cacheData = \json_decode(Signing::getPublicKeysResponse()->getBody()->getContents(), true);
    $cacheData['retrieved_at'] = Carbon::now()->subDay()->timestamp;
    $cacheData = $encrypter->encrypt($cacheData);

    $cache = Mockery::mock(\Psr\SimpleCache\CacheInterface::class);
    $cache->shouldReceive('has')->andReturnTrue();
    $cache->shouldReceive('set')->andReturnTrue();
    $cache->shouldReceive('get')->andReturn($cacheData);

    $body = WebhookPayload::paymentExecuted();

    \rawClient([Signing::getPublicKeysResponse()])
        ->cache($cache, '31c8d81a110849f83131541b9f67c3cba9c7e0bb103bc4dd19377f0fdf2d924b')
        ->create()
        ->webhook()
        ->body($body)
        ->path(Signing::getPath())
        ->headers(Signing::getHeaders($body))
        ->execute();

    $sentRequests = \getSentHttpRequests();
    \expect($sentRequests[0]->getUri()->getPath())->toBe('/.well-known/jwks');
    \expect($sentRequests[0]->getMethod())->toBe('GET');
});

\it('fetches keys if keys are not cached', function () {
    $cache = Mockery::mock(\Psr\SimpleCache\CacheInterface::class);
    $cache->shouldReceive('has')->andReturnFalse();
    $cache->shouldReceive('set')->andReturnTrue();

    $body = WebhookPayload::paymentExecuted();

    \rawClient([Signing::getPublicKeysResponse()])
        ->cache($cache, '31c8d81a110849f83131541b9f67c3cba9c7e0bb103bc4dd19377f0fdf2d924b')
        ->create()
        ->webhook()
        ->body($body)
        ->path(Signing::getPath())
        ->headers(Signing::getHeaders($body))
        ->execute();

    $sentRequests = \getSentHttpRequests();
    \expect($sentRequests[0]->getUri()->getPath())->toBe('/.well-known/jwks');
    \expect($sentRequests[0]->getMethod())->toBe('GET');
});

\it('refetches keys on invalid signature if cache exists', function () {
    $key = '31c8d81a110849f83131541b9f67c3cba9c7e0bb103bc4dd19377f0fdf2d924b';
    $encrypter = new Encrypter(\hex2bin($key), Encryption::ALGORITHM);

    $publicKey = JWKFactory::createECKey('P-521')->toPublic()->jsonSerialize();
    $publicKey['alg'] = 'ES512';
    $publicKey['kid'] = Signing::getKid();
    $cacheData = [
        'keys' => [$publicKey],
        'retrieved_at' => Carbon::now()->timestamp,
    ];
    $cacheData = $encrypter->encrypt($cacheData);

    $cache = Mockery::mock(\Psr\SimpleCache\CacheInterface::class);
    $cache->shouldReceive('has')->andReturnTrue();
    $cache->shouldReceive('set')->andReturnTrue();
    $cache->shouldReceive('get')->andReturn($cacheData);

    $body = WebhookPayload::paymentExecuted();

    \rawClient([Signing::getPublicKeysResponse()])
        ->cache($cache, '31c8d81a110849f83131541b9f67c3cba9c7e0bb103bc4dd19377f0fdf2d924b')
        ->create()
        ->webhook()
        ->body($body)
        ->path(Signing::getPath())
        ->headers(Signing::getHeaders($body))
        ->execute();

    $sentRequests = \getSentHttpRequests();
    \expect($sentRequests[0]->getUri()->getPath())->toBe('/.well-known/jwks');
    \expect($sentRequests[0]->getMethod())->toBe('GET');
});

\it('does not refetch keys on invalid signature if no cache exists', function () {
    \webhook(WebhookPayload::paymentExecuted())->execute();
    \expect(\getSentHttpRequests())->toHaveLength(1);
});

\it('throws error on invalid signature after refetch', function () {
    $key = '31c8d81a110849f83131541b9f67c3cba9c7e0bb103bc4dd19377f0fdf2d924b';
    $encrypter = new Encrypter(\hex2bin($key), Encryption::ALGORITHM);

    $publicKey = JWKFactory::createECKey('P-521')->toPublic()->jsonSerialize();
    $publicKey['alg'] = 'ES512';
    $publicKey['kid'] = Signing::getKid();
    $cacheData = [
        'keys' => [$publicKey],
        'retrieved_at' => Carbon::now()->timestamp,
    ];
    $cacheData = $encrypter->encrypt($cacheData);

    $cache = Mockery::mock(\Psr\SimpleCache\CacheInterface::class);
    $cache->shouldReceive('has')->andReturnTrue();
    $cache->shouldReceive('set')->andReturnTrue();
    $cache->shouldReceive('get')->andReturn($cacheData);

    $body = WebhookPayload::paymentExecuted();

    $verificationFailed = false;

    try {
        \rawClient([new Response(200, [], $cacheData)])
            ->cache($cache, '31c8d81a110849f83131541b9f67c3cba9c7e0bb103bc4dd19377f0fdf2d924b')
            ->create()
            ->webhook()
            ->body($body)
            ->path(Signing::getPath())
            ->headers(Signing::getHeaders($body))
            ->execute();
    } catch (WebhookVerificationFailedException $e) {
        $verificationFailed = true;
    }

    \expect(\getSentHttpRequests())->toHaveLength(1);
    \expect($verificationFailed)->toBeTrue();
});
