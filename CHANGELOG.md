# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/), and this project adheres
to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [3.0.2] - 2025-02-27

### Added

- Support for SignUp Plus authentication link creation
- Support for SignUp Plus user data retrieval

## [3.0.1] - 2025-01-09

### Changed

- Widened support for truelayer-signing

## [3.0.0] - 2024-12-04

### Added

- Payment authorized webhook (#69)
- Payment creditable webhook (#69)
- Payment settlement stalled webhook (#69)
- `getMetadata()` on payment webhooks (#69)
- `getPaymentSource()` on payment authorized, executed, settled and failed webhooks (#69)
- `getSchemeId()` on refund executed and payout executed webhooks
- Support for external account beneficiary on payout executed webhook
- Support for metadata field on payout creation, retrieval and
  webhooks ([#68](https://github.com/TrueLayer/truelayer-php/pull/68))
- Support for metadata field on refund creation, retrieval and
  webhooks ([#68](https://github.com/TrueLayer/truelayer-php/pull/68))
- Support for payment cancellation ([#67](https://github.com/TrueLayer/truelayer-php/pull/67))
- Support for date of birth of external account beneficiary on payout creation
- Support for address of external account beneficiary on payout creation
- Support for statement_reference of merchant account beneficiary
- Support for payout scheme selection

### Changed

- Fully separated payment and payout beneficiaries
- Moved all beneficiary classes to new namespace
- Payment method removed from `PaymentEventInterface` and now only available on payment authorized, executed, settled
  and failed webhooks
- Moved payment related scheme selection to new namespace

## [2.6.0] - 2024-10-10

### Added

- Added hash of clientId, scopes and clientSecret to cacheKey of AccessToken

## [2.5.0] - 2024-08-13

### Added

- Support for remitter verification for Merchant Account beneficiary payments (#61)
- Support for setting the user political exposure on payment creation (#62)
- Support for business account payouts beneficiary (#63)
- Support for setting the risk assessment on payment creation (#64)

## [2.4.0] - 2024-08-12

### Added

- Support for `preselected` provider selection under the Bank Transfer payment method
- Support for `preselected` payment scheme under the preselected provider selection

## [2.3.0] - 2024-07-18

### Added

- Support for `attempt_failed` payment status

## [2.2.0] - 2024-07-15

### Added

- Support for `retry` object on bank transfer payment method

## [2.1.0] - 2024-05-16

### Added

- Support for scheme selection configuration

## [2.0.1] - 2024-04-24

### Added

- Additional supported countries in TrueLayer\Constants\Countries

### Fixed

- Using provider filters without specifying an excluded provider id would return an error

## [2.0.0] - 2024-03-26

### Changed

- Removed Illuminate dependencies
- Removed input validation and `TrueLayer\Exception\ValidationException`
- Minimum PHP version supported is 8.1

## [1.7.0] - 2024-02-13

### Changed

- TrueLayer API paths now include the API version

## [1.6.0] - 2024-02-09

### Added

- Support for setting the user's address using `$client->user()->address()`
- Support for setting the user's date of birth using `$client->user()->dateOfBirth()`

## [1.5.0] - 2024-01-12

### Added

- New `$client->paymentAuthorizationFlow()` and `$createdPayment->authorizationFlow()` methods for starting the
  authorization flow that are better aligned with TrueLayer APIs
- Missing documentation for starting payment authorization

### Changed

- Deprecated `$client->startPaymentAuthorization()` and `$createdPayment->startAuthorization()`

## [1.4.0] - 2023-11-20

### Added

- Support for custom idempotency keys

## [1.3.1] - 2023-05-09

### Fixed

- Make metadata field optional

## [1.3.0] - 2023-05-09

### Added

- Added support for the payment's metadata field

## [1.2.1] - 2023-05-05

### Changed

- Widen ramsey/uuid constraints

## [1.2.0] - 2023-05-02

### Added

- HTTP client auto discovery

## [1.1.1] - 2023-03-14

### Fixed

- PaymentSettledEventInterface should extend PaymentEventInterface

## [1.1.0] - 2023-02-01

### Added

- Support setting and getting the reference on the Merchant Account beneficiary

## [1.0.0] - 2023-01-27

### Removed

- User info from the `/GET payment` response

## [0.0.6] - 2023-01-20

### Fixed

- Datetime parsing on php7.4

## [0.0.5] - 2022-12-08

### Added

- Custom scopes

## [0.0.4] - 2022-07-27

### Added

- Support for illuminate v9 packages

## [0.0.3] - 2022-07-21

### Added

- Support for webhook signature verification & handlers

## [0.0.2] - 2022-07-04

### Added

- Support for refunds
- Custom User-Agent header for HTTP requests
- Updated sandbox endpoint

### Changed

- Cache key for TrueLayer's client credentials token changed from `TL_SDK_AUTH-TOKEN` to `TL_CLIENT_AUTH-TOKEN`

## [0.0.1] - 2022-05-05

### Added

- Added support for creating & retrieving payments.
- Added support for creating payouts.
- Added support for retrieving merchant accounts.
- Added support for payment authorization (no support for form action yet).
- Added support for HPP link generation.
- Added support for custom requests. It provides authentication, request signing, error handling and retries for free.
