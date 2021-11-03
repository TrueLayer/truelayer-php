<?php

namespace TrueLayer\Contracts;

use Psr\Http\Message\UriInterface;

interface HttpClient
{
    public function get(UriInterface $uri, ?string $accessToken);
}