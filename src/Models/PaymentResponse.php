<?php

namespace TrueLayer\Models;

class PaymentResponse
{
    private \stdClass $response;

    public function __construct(string $jsonResponse)
    {
        $this->response = json_decode($jsonResponse);
    }
}
