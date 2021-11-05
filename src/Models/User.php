<?php

namespace TrueLayer\Models;

class User
{
    public function toArray(): array
    {
        return [
            'type' => 'new',
            'name' => 'Test',
            'email' => 'test@test.com',
        ];
    }
}
