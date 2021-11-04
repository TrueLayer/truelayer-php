<?php

declare(strict_types=1);

namespace TrueLayer\Contracts;

interface HttpClient
{
    public function get(UriInterface $uri, ?string $accessToken = null);
}
