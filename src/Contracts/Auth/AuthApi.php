<?php

namespace TrueLayer\Contracts\Auth;

use TrueLayer\Contracts\Auth\Resources\AuthToken;

interface AuthApi
{
    public function GetAuthToken(): AuthToken;
}