<?php

declare(strict_types=1);

namespace TrueLayer\Tests\Integration\Mocks;

use GuzzleHttp\Psr7\Response;

class SubMerchantPaymentResponse
{
    public const CREATED_WITH_BUSINESS_CLIENT = [
        'id' => '5a2a0a0d-d3ad-4740-860b-45a01bcc17ac',
        'resource_token' => 'the-token',
        'user' => [
            'id' => 'ba6412a3-8b2c-4d33-a40b-9964062979da',
        ],
        'sub_merchants' => [
            'ultimate_counterparty' => [
                'type' => 'business_client',
                'address' => [
                    'address_line1' => '123 Test Street',
                    'city' => 'London',
                    'zip' => 'SW1A 1AA',
                    'country_code' => 'GB',
                ],
                'commercial_name' => 'Test Business Ltd',
                'mcc' => '5411',
            ],
        ],
    ];

    public const CREATED_WITH_BUSINESS_DIVISION = [
        'id' => '5a2a0a0d-d3ad-4740-860b-45a01bcc17ac',
        'resource_token' => 'the-token',
        'user' => [
            'id' => 'ba6412a3-8b2c-4d33-a40b-9964062979da',
        ],
        'sub_merchants' => [
            'ultimate_counterparty' => [
                'type' => 'business_division',
                'id' => 'div-123',
                'name' => 'Marketing Division',
            ],
        ],
    ];

    public const CREATED_WITH_REGISTRATION_NUMBER = [
        'id' => '5a2a0a0d-d3ad-4740-860b-45a01bcc17ac',
        'resource_token' => 'the-token',
        'user' => [
            'id' => 'ba6412a3-8b2c-4d33-a40b-9964062979da',
        ],
        'sub_merchants' => [
            'ultimate_counterparty' => [
                'type' => 'business_client',
                'registration_number' => '12345678',
                'commercial_name' => 'Registered Business Ltd',
                'mcc' => '7372',
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
    public static function createdWithBusinessDivision(): Response
    {
        return new Response(200, [], \json_encode(self::CREATED_WITH_BUSINESS_DIVISION));
    }

    /**
     * @return Response
     */
    public static function createdWithRegistrationNumber(): Response
    {
        return new Response(200, [], \json_encode(self::CREATED_WITH_REGISTRATION_NUMBER));
    }

    public static function authorizationRequiredWithBusinessClient(): Response
    {
        return new Response(200, [], \json_encode([
            'id' => '56bbff85-9504-4cba-a63b-c781745ad3ed',
            'amount_in_minor' => 1000,
            'currency' => 'GBP',
            'user' => [
                'id' => 'c4e754fd-8b0d-40fe-bd61-36622b7477a4',
            ],
            'payment_method' => [
                'type' => 'bank_transfer',
                'beneficiary' => [
                    'type' => 'external_account',
                    'account_identifier' => [
                        'type' => 'sort_code_account_number',
                        'sort_code' => '010203',
                        'account_number' => '12345678',
                    ],
                    'account_holder_name' => 'Test Recipient',
                    'reference' => 'TEST',
                ],
                'provider_selection' => [
                    'type' => 'user_selected',
                ],
            ],
            'sub_merchants' => [
                'ultimate_counterparty' => [
                    'type' => 'business_client',
                    'address' => [
                        'address_line1' => '456 Business Road',
                        'city' => 'Manchester',
                        'zip' => 'M1 1AA',
                        'country_code' => 'GB',
                    ],
                    'commercial_name' => 'Test Commerce Ltd',
                    'mcc' => '5999',
                ],
            ],
            'created_at' => '2022-02-04T13:40:23.798415Z',
            'status' => 'authorization_required',
        ]));
    }

    public static function executedWithBusinessClient(): Response
    {
        return new Response(200, [], \json_encode([
            'id' => '56bbff85-9504-4cba-a63b-c781745ad3ed',
            'amount_in_minor' => 1000,
            'currency' => 'GBP',
            'user' => [
                'id' => 'c4e754fd-8b0d-40fe-bd61-36622b7477a4',
            ],
            'payment_method' => [
                'type' => 'bank_transfer',
                'beneficiary' => [
                    'type' => 'external_account',
                    'account_identifier' => [
                        'type' => 'sort_code_account_number',
                        'sort_code' => '010203',
                        'account_number' => '12345678',
                    ],
                    'account_holder_name' => 'Test Recipient',
                    'reference' => 'TEST',
                ],
                'provider_selection' => [
                    'type' => 'user_selected',
                ],
            ],
            'sub_merchants' => [
                'ultimate_counterparty' => [
                    'type' => 'business_client',
                    'address' => [
                        'address_line1' => '456 Business Road',
                        'city' => 'Manchester',
                        'zip' => 'M1 1AA',
                        'country_code' => 'GB',
                    ],
                    'commercial_name' => 'Test Commerce Ltd',
                    'mcc' => '5999',
                ],
            ],
            'created_at' => '2022-02-04T13:40:23.798415Z',
            'status' => 'executed',
            'authorization_flow' => [
                'configuration' => [
                    'provider_selection' => (object) [],
                    'redirect' => [
                        'return_uri' => 'https://console.truelayer.com/redirect-page',
                    ],
                ],
            ],
            'executed_at' => '2022-02-04T14:12:07.705938Z',
        ]));
    }
}