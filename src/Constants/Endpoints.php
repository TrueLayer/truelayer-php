<?php

declare(strict_types=1);

namespace TrueLayer\Constants;

class Endpoints
{
    public const AUTH_PROD_URL = 'https://auth.truelayer.com';
    public const AUTH_SANDBOX_URL = 'https://auth.truelayer-sandbox.com';

    public const API_PROD_URL = 'https://api.truelayer.com';
    public const API_SANDBOX_URL = 'https://test-pay-api.truelayer-sandbox.com';

    public const HPP_PROD_URL = 'https://payment.truelayer.com/payments';
    public const HPP_SANDBOX_URL = 'https://payment.truelayer-sandbox.com/payments';

    public const TOKEN = '/connect/token';
    public const PAYMENTS_CREATE = '/payments';
    public const PAYMENTS_RETRIEVE = '/payments/{id}';
    public const PAYMENTS_START_AUTH_FLOW = '/payments/{id}/authorization-flow';
    public const PAYMENTS_SUBMIT_PROVIDER = '/payments/{id}/authorization-flow/actions/provider-selection';

    public const MERCHANT_ACCOUNTS = '/merchant-accounts';
    public const TRANSACTIONS = '/merchant-accounts/{id}/transactions';

    public const PAYOUTS_CREATE = '/payouts';
    public const PAYOUTS_RETRIEVE = '/payouts/{id}';
}
