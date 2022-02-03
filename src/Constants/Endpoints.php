<?php

declare(strict_types=1);

namespace TrueLayer\Constants;

class Endpoints
{
    public const AUTH_PROD_URL = 'https://auth.truelayer.com';
    public const AUTH_SANDBOX_URL = 'https://auth.t7r.dev';

    public const API_PROD_URL = 'https://api.truelayer.com';
    public const API_SANDBOX_URL = 'https://test-pay-api.t7r.dev';

    public const HPP_PROD_URL = 'https://payment.truelayer.com/payments';
    public const HPP_SANDBOX_URL = 'https://payment.t7r.dev/payments';

    public const TOKEN = '/connect/token';
    public const PAYMENTS = '/payments';
    public const MERCHANT_ACCOUNTS = '/merchant-accounts';
    public const PAYOUTS = '/payouts';
    public const TRANSACTIONS = '/merchant-accounts/{id}/transactions';
}
