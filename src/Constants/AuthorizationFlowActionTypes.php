<?php

declare(strict_types=1);

namespace TrueLayer\Constants;

class AuthorizationFlowActionTypes
{
    public const PROVIDER_SELECTION = 'provider_selection';
    public const REDIRECT = 'redirect';
    public const WAIT = 'wait';
    public const RETRY = 'retry';
}
