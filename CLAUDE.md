# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Development Commands

### Testing
- `composer run integration-tests` - Run integration tests using Pest
- `composer run acceptance-tests` - Run acceptance tests using Pest
- `vendor/bin/pest --test-directory tests/integration` - Run integration tests directly
- `vendor/bin/pest --test-directory tests/acceptance` - Run acceptance tests directly

### Code Quality
- `composer run analyse` - Run PHPStan static analysis (level 9)
- `composer run cs-fix` - Run PHP CS Fixer for code style fixes
- `composer run checks` - Run all checks (analysis, code style, and all tests)

### PHPUnit Configuration
- Integration and acceptance tests use Pest framework
- Test configuration in `phpunit.xml.dist`
- Test helpers available in `tests/acceptance/Pest.php` and `tests/integration/Pest.php`

## Architecture Overview

### Core Client Structure
The library follows a factory-based architecture with dependency injection:

- **Client Entry Point**: `TrueLayer\Client::configure()` returns a `ClientConfigInterface` for fluent configuration
- **Client Factory**: `ClientFactory` creates the main `Client` instance with all dependencies
- **API Client**: Decorated with middleware for authentication, signing, retries, and idempotency
- **Entity Factory**: Creates domain entities from API responses
- **API Factory**: Creates API service instances (payments, payouts, merchant accounts, webhooks)

### Key Components

#### API Client Decorators (Applied in Order)
1. `AccessTokenDecorator` - Handles OAuth2 authentication
2. `ExponentialBackoffDecorator` - Implements retry logic with backoff
3. `SigningDecorator` - Signs requests using TrueLayer's signing library
4. `IdempotencyKeyDecorator` - Manages idempotency keys for safe retries
5. `TLAgentDecorator` - Adds user agent headers

#### Domain Entities
- **Payments**: Payment creation, retrieval, authorization flows, refunds
- **Payouts**: External account payouts, business account payouts, payment source refunds
- **Merchant Accounts**: Account management and balance retrieval
- **Webhooks**: Event handling with signature verification
- **Account Identifiers**: Support for IBAN, SCAN, NRB, BBAN account types

#### Builder Pattern Usage
The library extensively uses builders for complex entity creation:
- `BeneficiaryBuilder` for payment beneficiaries
- `PaymentMethodBuilder` for payment methods
- `ProviderSelectionBuilder` for provider filtering
- `SchemeSelectionBuilder` for payment scheme selection

### Configuration and Environment
- Supports both sandbox and production environments
- Configurable via environment variables or programmatic setup
- Optional caching support for OAuth tokens (PSR-16 compatible)
- Requires PSR-18 HTTP client implementation

### Security Features
- Request signing using private keys (EC512 supported)
- Webhook signature verification
- OAuth2 client credentials flow
- Encrypted token caching when cache is enabled

### Constants and Enums
Located in `src/Constants/` directory:
- Payment statuses, currencies, countries
- Webhook event types
- API endpoints and HTTP methods
- Account identifier types and schemes

### Error Handling
Custom exceptions in `src/Exceptions/`:
- `ApiResponseUnsuccessfulException` for API errors
- `SignerException` for signing failures
- `WebhookVerificationFailedException` for webhook security
- All extend base `Exception` class

### API Reference
- Use `Payments_API_V3_specs` file as the Payments API specs reference (as noted in CLAUDE.local.md)
- Comprehensive examples available in README.md for all major operations

## Testing Notes
- Acceptance tests require environment variables in `.env` file
- Integration tests use mocked HTTP responses
- Test helpers available for common operations like payment creation
- Mock responses and test utilities in `tests/integration/Mocks/`