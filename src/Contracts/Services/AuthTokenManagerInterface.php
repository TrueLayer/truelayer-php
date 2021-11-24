<?php

namespace TrueLayer\Contracts\Services;

use TrueLayer\Exceptions\AuthTokenRetrievalFailure;

interface AuthTokenManagerInterface
{
    /**
     * @return string
     * @throws AuthTokenRetrievalFailure
     */
    public function getAccessToken(): string;

    /**
     * @return void
     */
    public function fetchAuthToken(): void;
}
