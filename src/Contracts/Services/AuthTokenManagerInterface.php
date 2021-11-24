<?php

namespace TrueLayer\Contracts\Services;

use TrueLayer\Exceptions\AuthTokenRetrievalFailure;

interface AuthTokenManagerInterface
{
    /**
     * @throws AuthTokenRetrievalFailure
     *
     * @return string
     */
    public function getAccessToken(): string;

    /**
     * @return void
     */
    public function fetchAuthToken(): void;
}
