# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/), and this project adheres
to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

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
