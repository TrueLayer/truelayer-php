<?php

namespace TrueLayer;

use TrueLayer\Contracts\Auth\Resources\AuthToken as IAuthToken;

class AuthToken implements IAuthToken
{
    private \stdClass $token;

    public function __construct(string $token)
    {
        $this->token = json_decode($token);
    }

    public function getToken(): string
    {
        return $this->token->access_token;
    }

    public function getTokenType(): string
    {
        return $this->token->token_type;
    }

    public function getExpiresIn(): int
    {
        // TODO: Implement getExpiresIn() method.
    }

    public function getScopes(): array
    {
        // TODO: Implement getScopes() method.
    }

    public function isScoped(): bool
    {
        // TODO: Implement isScoped() method.
    }
}
