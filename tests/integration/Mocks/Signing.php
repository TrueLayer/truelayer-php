<?php

declare(strict_types=1);

namespace TrueLayer\Tests\Integration\Mocks;

use GuzzleHttp\Psr7\Response;
use Jose\Component\Core\JWK;
use Jose\Component\KeyManagement\JWKFactory;
use Ramsey\Uuid\Uuid;
use TrueLayer\Signing\Contracts\Signer;

class Signing
{
    protected static ?JWK $public = null;

    protected static ?JWK $private = null;

    protected static ?string $kid = null;

    /**
     * @return string
     */
    public static function getKid(): string
    {
        if (!self::$kid) {
            self::make();
        }

        return self::$kid;
    }

    /**
     * @return JWK
     */
    public static function getPublic(): JWK
    {
        if (!self::$public) {
            self::make();
        }

        return self::$public;
    }

    /**
     * @return Response
     */
    public static function getPublicKeysResponse(): Response
    {
        $key = \array_merge(self::getPublic()->jsonSerialize(), [
            'alg' => 'ES512',
            'kid' => self::getKid(),
        ]);

        $payload = ['keys' => [$key]];

        return new Response(200, [], \json_encode($payload));
    }

    /**
     * @return JWK
     */
    public static function getPrivate(): JWK
    {
        if (!self::$private) {
            self::make();
        }

        return self::$private;
    }

    /**
     * @return Signer
     */
    public static function getSigner(): Signer
    {
        return \TrueLayer\Signing\Signer::signWithKey(self::getKid(), self::getPrivate());
    }

    /**
     * @param string      $body
     * @param string|null $path
     * @param array|null  $headers
     *
     * @return string
     */
    public static function sign(string $body, string $path = null, array $headers = null): string
    {
        return self::getSigner()
            ->method('POST')
            ->path($path ?: self::getPath())
            ->headers($headers ?: self::getHeaders())
            ->body($body)
            ->sign();
    }

    /**
     * @return string
     */
    public static function getPath(): string
    {
        return '/test';
    }

    /**
     * @param string|null $body
     * @param string|null $path
     * @param array|null  $headers
     *
     * @return string[]
     */
    public static function getHeaders(string $body = null, string $path = null, array $headers = null): array
    {
        $headers = [
            'x-tl-webhook-timestamp' => '2022-02-16T16:21:14Z',
        ];

        if ($body !== null) {
            $headers['tl-signature'] = self::sign($body, $path, $headers);
        }

        return $headers;
    }

    private static function make()
    {
        self::$private = JWKFactory::createECKey('P-521');
        self::$kid = Uuid::uuid4()->toString();
        self::$public = self::$private->toPublic();
    }
}
