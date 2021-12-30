<?php

declare(strict_types=1);

namespace TrueLayer\Tests\Mocks;

use GuzzleHttp\Psr7\Response;

class PaymentResponse
{
    /**
     * @return Response
     */
    public static function invalidParameters(): Response
    {
        $body = '{"type":"https://docs.truelayer.com/docs/error-types#invalid-parameters","title":"Invalid Parameters","status":400,"detail":"The request body was invalid.","trace_id":"8246b93e2f53ad4a9a4b9af884dcf457","errors":{"beneficiary":["Value is required."],"user":["Value is required."]}}';

        return new Response(400, [], $body);
    }

    /**
     * @return Response
     */
    public function success(): Response
    {
    }
}
