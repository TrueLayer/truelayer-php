<?php

declare(strict_types=1);

namespace TrueLayer\Contracts\Auth;

use TrueLayer\Contracts\Auth\Resources\AuthToken;

interface AuthApi
{
    public function getAuthToken(): AuthToken;
}
