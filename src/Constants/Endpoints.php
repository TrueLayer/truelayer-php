<?php

declare(strict_types=1);

namespace TrueLayer\Constants;

class Endpoints
{
    public const AUTH_PROD_URL = 'https://auth.truelayer.com';
    public const AUTH_SANDBOX_URL = 'https://auth.truelayer-sandbox.com';

    public const API_PROD_URL = 'https://api.truelayer.com';
    public const API_SANDBOX_URL = 'https://api.truelayer-sandbox.com';

    public const HPP_PROD_URL = 'https://payment.truelayer.com/payments';
    public const HPP_SANDBOX_URL = 'https://payment.truelayer-sandbox.com/payments';

    public const WEBHOOKS_PROD_URL = 'https://webhooks.truelayer.com';
    public const WEBHOOKS_SANDBOX_URL = 'https://webhooks.truelayer-sandbox.com';
    public const JWKS = '/.well-known/jwks';

    public const TOKEN = '/connect/token';
    public const PAYMENTS_CREATE = '/v3/payments';
    public const PAYMENTS_RETRIEVE = '/v3/payments/{id}';
    public const PAYMENTS_CANCEL = '/v3/payments/{id}/actions/cancel';
    public const PAYMENTS_START_AUTH_FLOW = '/v3/payments/{id}/authorization-flow';
    public const PAYMENTS_SUBMIT_PROVIDER = '/v3/payments/{id}/authorization-flow/actions/provider-selection';
    public const PAYMENTS_REFUNDS_CREATE = '/v3/payments/{id}/refunds';
    public const PAYMENTS_REFUNDS_RETRIEVE_ALL = '/v3/payments/{id}/refunds';
    public const PAYMENTS_REFUNDS_RETRIEVE = '/v3/payments/{id}/refunds/{refund_id}';
    public const PAYMENTS_PROVIDER_RETURN = '/v3/payments-provider-return';

    public const MERCHANT_ACCOUNTS = '/v3/merchant-accounts';
    public const TRANSACTIONS = '/v3/merchant-accounts/{id}/transactions';

    public const PAYOUTS_CREATE = '/v3/payouts';
    public const PAYOUTS_RETRIEVE = '/v3/payouts/{id}';
    public const SIGNUP_PLUS_AUTH = '/signup-plus/authuri';
}
