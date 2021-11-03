<?php

namespace TrueLayer\Contracts\Auth\Resources;

interface AuthToken
{
    public function getToken(): string;

    public function getTokenType(): string;

    public function getExpiresIn(): int;

    public function getScopes(): array;

    public function isScoped(): bool;
}