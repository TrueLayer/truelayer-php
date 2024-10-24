<?php

declare(strict_types=1);

use TrueLayer\Constants\DateTime;
use TrueLayer\Interfaces\AccountIdentifier\ScanDetailsInterface;
use TrueLayer\Interfaces\Payout\Beneficiary\ExternalAccountBeneficiaryInterface;
use TrueLayer\Interfaces\Payout\Beneficiary\PaymentSourceBeneficiaryInterface;
use TrueLayer\Interfaces\Payout\PayoutAuthorizedInterface;
use TrueLayer\Interfaces\Payout\PayoutExecutedInterface;
use TrueLayer\Interfaces\Payout\PayoutFailedInterface;
use TrueLayer\Interfaces\Payout\PayoutPendingInterface;
use TrueLayer\Interfaces\Payout\PayoutRetrievedInterface;
use TrueLayer\Tests\Integration\Mocks\PayoutResponse;

function assertPayoutCommon(PayoutRetrievedInterface $payout)
{
    \expect($payout->getId())->toBe('cbc98b01-6d4e-4137-a6fc-1659a26e5c55');
    \expect($payout->getStatus())->toBeString();
    \expect($payout->getMerchantAccountId())->toBe('822f8dfe-0874-481d-b966-5b14f767792f');
    \expect($payout->getCurrency())->toBe('GBP');
    \expect($payout->getAmountInMinor())->toBe(1);
    \expect($payout->getCreatedAt()->format(DateTime::FORMAT))->toBe('2022-04-01T19:47:12.642232Z');

    /** @var ExternalAccountBeneficiaryInterface $beneficiary */
    $beneficiary = $payout->getBeneficiary();

    \expect($beneficiary)->toBeInstanceOf(ExternalAccountBeneficiaryInterface::class);
    \expect($beneficiary->getAccountHolderName())->toBe('Test');
    \expect($beneficiary->getReference())->toBe('Test reference');

    /** @var ScanDetailsInterface $accountIdentifier */
    $accountIdentifier = $beneficiary->getAccountIdentifier();

    \expect($accountIdentifier)->toBeInstanceOf(ScanDetailsInterface::class);
    \expect($accountIdentifier->getAccountNumber())->toBe('12345678');
    \expect($accountIdentifier->getSortCode())->toBe('121212');
}

\it('handles pending payout', function () {
    $payout = \client(PayoutResponse::pending())->getPayout('1');
    \expect($payout)->toBeInstanceOf(PayoutPendingInterface::class);
    \expect($payout->getStatus())->toBe('pending');
    \assertPayoutCommon($payout);
});

\it('handles authorized payout', function () {
    $payout = \client(PayoutResponse::authorized())->getPayout('1');
    \expect($payout)->toBeInstanceOf(PayoutAuthorizedInterface::class);
    \expect($payout->getStatus())->toBe('authorized');
    \assertPayoutCommon($payout);
});

\it('handles executed payout', function () {
    /** @var PayoutExecutedInterface $payout */
    $payout = \client(PayoutResponse::executed())->getPayout('1');

    \expect($payout)->toBeInstanceOf(PayoutExecutedInterface::class);
    \expect($payout->getStatus())->toBe('executed');
    \expect($payout->getExecutedAt()->format(DateTime::FORMAT))->toBe('2022-04-01T19:47:13.642232Z');

    \assertPayoutCommon($payout);
});

\it('handles failed payout', function () {
    /** @var PayoutFailedInterface $payout */
    $payout = \client(PayoutResponse::failed())->getPayout('1');

    \expect($payout)->toBeInstanceOf(PayoutFailedInterface::class);
    \expect($payout->getStatus())->toBe('failed');
    \expect($payout->getFailedAt()->format(DateTime::FORMAT))->toBe('2022-04-01T19:48:14.317504Z');
    \expect($payout->getFailureReason())->toBeNull();

    \assertPayoutCommon($payout);
});

\it('handles payout with payment source', function () {
    $payout = \client(PayoutResponse::pendingWithPaymentSource())->getPayout('1');
    \expect($payout)->toBeInstanceOf(PayoutPendingInterface::class);
    \expect($payout->getStatus())->toBe('pending');

    /** @var PaymentSourceBeneficiaryInterface $beneficiary */
    $beneficiary = $payout->getBeneficiary();

    \expect($beneficiary)->toBeInstanceOf(PaymentSourceBeneficiaryInterface::class);
    \expect($beneficiary->getReference())->toBe('Test reference');
    \expect($beneficiary->getPaymentSourceId())->toBe('source1');
    \expect($beneficiary->getUserId())->toBe('user1');
});

\it('retrieves the payout metadata', function () {
    /** @var PayoutExecutedInterface $payout */
    $payout = \client(PayoutResponse::executedWithMetadata())->getPayout('1');

    \expect($payout->getMetadata())->toBe(['foo' => 'bar']);
});
