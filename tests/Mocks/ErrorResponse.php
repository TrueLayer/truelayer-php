<?php

declare(strict_types=1);

namespace TrueLayer\Tests\Mocks;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

class ErrorResponse
{
    /**
     * @return ResponseInterface
     */
    public static function unauthenticated(): ResponseInterface
    {
        return self::toResponse([
            'type' => 'https://docs.truelayer.com/docs/error-types#unauthenticated',
            'title' => 'Unauthenticated',
            'status' => 401,
            'trace_id' => '96ce50247f87f540bb2d86771b3728b8',
            'detail' => 'A Bearer token must be provided in the Authorization header.',
        ]);
    }

    /**
     * @return ResponseInterface
     */
    public static function forbidden(): ResponseInterface
    {
        return self::toResponse([
            'type' => 'https://docs.truelayer.com/docs/error-types#forbidden',
            'title' => 'Forbidden',
            'status' => 403,
            'trace_id' => '96ce50247f87f540bb2d86771b3728b8',
            'detail' => 'The token used for Authorization is not unauthorized to perform the request.',
        ]);
    }

    /**
     * @return ResponseInterface
     */
    public static function invalidParameters(): ResponseInterface
    {
        return self::toResponse([
            'type' => 'https://docs.truelayer.com/docs/error-types#invalid-parameters',
            'title' => 'Invalid Parameters',
            'status' => 400,
            'trace_id' => '96ce50247f87f540bb2d86771b3728b8',
            'detail' => 'The request body was invalid.',
            'errors' => [
                'beneficiary.type' => [
                    'Must be either merchant_account or external_account',
                ],
            ],
        ]);
    }

    /**
     * @return ResponseInterface
     */
    public static function conflict(): ResponseInterface
    {
        return self::toResponse([
            'type' => 'https://docs.truelayer.com/docs/error-types#conflict',
            'title' => 'Conflict',
            'status' => 409,
            'trace_id' => '96ce50247f87f540bb2d86771b3728b8',
            'detail' => 'Another request conflicted with the current request.',
        ]);
    }

    /**
     * @return ResponseInterface
     */
    public static function idempotencyKeyConcurrencyConflict(): ResponseInterface
    {
        return self::toResponse([
            'type' => 'https://docs.truelayer.com/docs/error-types#idempotency-key-concurrency-conflict',
            'title' => 'Idempotency-Key Concurrency Conflict',
            'status' => 409,
            'trace_id' => '96ce50247f87f540bb2d86771b3728b8',
            'detail' => 'The Idempotency-Key value is being used for a concurrent request.',
        ]);
    }

    /**
     * @return ResponseInterface
     */
    public static function idempotencyKeyReuse(): ResponseInterface
    {
        return self::toResponse([
            'type' => 'https://docs.truelayer.com/docs/error-types#idempotency-key-reuse',
            'title' => 'Idempotency-Key Reuse',
            'status' => 422,
            'trace_id' => '96ce50247f87f540bb2d86771b3728b8',
            'detail' => 'The Idempotency-Key value has already been used for a different request.',
        ]);
    }

    /**
     * @return ResponseInterface
     */
    public static function providerError(): ResponseInterface
    {
        return self::toResponse([
            'type' => 'https://docs.truelayer.com/docs/error-types#provider-error',
            'title' => 'Provider Error',
            'status' => 502,
            'trace_id' => '96ce50247f87f540bb2d86771b3728b8',
            'detail' => 'Provider is temporarily unavailable, please retry.',
        ]);
    }

    /**
     * @param array $payload
     *
     * @return ResponseInterface
     */
    protected static function toResponse(array $payload): ResponseInterface
    {
        return new Response($payload['status'], [], \json_encode($payload));
    }
}
