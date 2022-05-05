# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [0.0.2] - 2022-05-05
### Added
- Support for webhook verification against TrueLayer's JWKS endpoints.

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
