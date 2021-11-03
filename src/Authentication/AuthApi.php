<?php

namespace TrueLayer\Authentication;

use TrueLayer\Contracts\Auth\AuthApi as IAuthApi;
use TrueLayer\Contracts\Auth\Resources\AuthToken;

class AuthApi implements IAuthApi
{
    private const PRODUCTION_URL = "https://auth.truelayer.com";
    private const SANDBOX_URL = "https://auth.truelayer-sandbox.com";

    public function __construct()
    {

    }

    public function GetAuthToken(): AuthToken
    {
        // TODO: Implement GetAuthToken() method.
    }
}