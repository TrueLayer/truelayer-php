<?php

namespace TrueLayer\Contracts;

interface HttpClient
{
    public function get(UriInterface $uri, ?string $accessToken = null);
}
