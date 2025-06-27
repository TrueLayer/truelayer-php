<?php

declare(strict_types=1);

namespace TrueLayer\Tests\Integration\Mocks;

use GuzzleHttp\Psr7\Response;

class SubMerchantPayoutResponse
{
    public const CREATED_WITH_BUSINESS_CLIENT = [
        'id' => 'ca9a3154-9151-44cf-b7cb-073c9e12ef91',
        'sub_merchants' => [
            'ultimate_counterparty' => [
                'type' => 'business_client',
                'address' => [
                    'address_line1' => '789 Payout Street',
                    'city' => 'Birmingham',
                    'zip' => 'B1 1AA',
                    'country_code' => 'GB',
                ],
                'commercial_name' => 'Payout Business Ltd',
                'mcc' => '6011',
            ],
        ],
    ];

    public const CREATED_WITH_REGISTRATION_NUMBER = [
        'id' => 'ca9a3154-9151-44cf-b7cb-073c9e12ef91',
        'sub_merchants' => [
            'ultimate_counterparty' => [
                'type' => 'business_client',
                'registration_number' => '87654321',
                'commercial_name' => 'Registered Payout Ltd',
                'mcc' => '7299',
            ],
        ],
    ];

    /**
     * @return Response
     */
    public static function createdWithBusinessClient(): Response
    {
        return new Response(200, [], \json_encode(self::CREATED_WITH_BUSINESS_CLIENT));
    }

    /**
     * @return Response
     */
    public static function createdWithRegistrationNumber(): Response
    {
        return new Response(200, [], \json_encode(self::CREATED_WITH_REGISTRATION_NUMBER));
    }

    public static function pendingWithBusinessClient(): Response
    {
        return new Response(200, [], \json_encode([
            'id' => 'cbc98b01-6d4e-4137-a6fc-1659a26e5c55',
            'merchant_account_id' => '822f8dfe-0874-481d-b966-5b14f767792f',
            'amount_in_minor' => 500,
            'currency' => 'GBP',
            'beneficiary' => [
                'type' => 'external_account',
                'account_holder_name' => 'Test Recipient',
                'account_identifier' => [
                    'type' => 'sort_code_account_number',
                    'sort_code' => '560029',
                    'account_number' => '26207729',
                ],
                'reference' => 'Test reference',
            ],
            'sub_merchants' => [
                'ultimate_counterparty' => [
                    'type' => 'business_client',
                    'address' => [
                        'address_line1' => '789 Payout Street',
                        'city' => 'Birmingham',
                        'zip' => 'B1 1AA',
                        'country_code' => 'GB',
                    ],
                    'commercial_name' => 'Payout Business Ltd',
                    'mcc' => '6011',
                ],
            ],
            'status' => 'pending',
            'created_at' => '2022-04-01T19:47:12.642232Z',
        ]));
    }

    public static function executedWithBusinessClient(): Response
    {
        return new Response(200, [], \json_encode([
            'id' => 'cbc98b01-6d4e-4137-a6fc-1659a26e5c55',
            'merchant_account_id' => '822f8dfe-0874-481d-b966-5b14f767792f',
            'amount_in_minor' => 500,
            'currency' => 'GBP',
            'beneficiary' => [
                'type' => 'external_account',
                'account_holder_name' => 'Test Recipient',
                'account_identifier' => [
                    'type' => 'sort_code_account_number',
                    'sort_code' => '560029',
                    'account_number' => '26207729',
                ],
                'reference' => 'Test reference',
            ],
            'sub_merchants' => [
                'ultimate_counterparty' => [
                    'type' => 'business_client',
                    'address' => [
                        'address_line1' => '789 Payout Street',
                        'city' => 'Birmingham',
                        'zip' => 'B1 1AA',
                        'country_code' => 'GB',
                    ],
                    'commercial_name' => 'Payout Business Ltd',
                    'mcc' => '6011',
                ],
            ],
            'status' => 'executed',
            'created_at' => '2022-04-01T19:47:12.642232Z',
            'authorized_at' => '2022-04-01T19:47:12.642232Z',
            'executed_at' => '2022-04-01T19:47:13.642232Z',
        ]));
    }
}