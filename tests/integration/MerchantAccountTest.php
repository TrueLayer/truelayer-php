<?php

declare(strict_types=1);

use TrueLayer\Constants\AccountIdentifierTypes;
use TrueLayer\Interfaces\AccountIdentifier\IbanDetailsInterface;
use TrueLayer\Interfaces\AccountIdentifier\ScanDetailsInterface;
use TrueLayer\Tests\Integration\Mocks\MerchantAccountResponse;

\it('retrieves merchant accounts', function () {
    $accounts = \client(MerchantAccountResponse::accountsList())->getMerchantAccounts();

    \expect($accounts[0]->getId())->toBeString();
    \expect($accounts[0]->getCurrency())->toBe(TrueLayer\Constants\Currencies::EUR);
    \expect($accounts[0]->getCurrentBalanceInMinor())->toBe(100000);
    \expect($accounts[0]->getAvailableBalanceInMinor())->toBe(100000);
    \expect($accounts[0]->getAccountHolderName())->toBe('John Doe');

    /** @var IbanDetailsInterface $accountIdentifier */
    $accountIdentifier = $accounts[0]->getAccountIdentifiers()[0];
    \expect($accountIdentifier)->toBeInstanceOf(IbanDetailsInterface::class);
    \expect($accountIdentifier->getIban())->toBe('GB38CLRB04066200003769');
    \expect($accountIdentifier->getType())->toBe(AccountIdentifierTypes::IBAN);

    \expect($accounts[1]->getId())->toBeString();
    \expect($accounts[1]->getCurrency())->toBe(TrueLayer\Constants\Currencies::GBP);
    \expect($accounts[1]->getCurrentBalanceInMinor())->toBe(100001);
    \expect($accounts[1]->getAvailableBalanceInMinor())->toBe(100001);
    \expect($accounts[1]->getAccountHolderName())->toBe('John Doe');

    /** @var ScanDetailsInterface $accountIdentifier */
    $accountIdentifier = $accounts[1]->getAccountIdentifiers()[0];
    \expect($accountIdentifier)->toBeInstanceOf(ScanDetailsInterface::class);
    \expect($accountIdentifier->getSortCode())->toBe('040662');
    \expect($accountIdentifier->getAccountNumber())->toBe('00003209');
    \expect($accountIdentifier->getType())->toBe(AccountIdentifierTypes::SORT_CODE_ACCOUNT_NUMBER);
});

\it('retrieves a merchant account', function () {
    $account = \client(MerchantAccountResponse::account())->getMerchantAccount('1');

    \expect($account->getId())->toBeString();
    \expect($account->getCurrency())->toBe(TrueLayer\Constants\Currencies::EUR);
    \expect($account->getCurrentBalanceInMinor())->toBe(100000);
    \expect($account->getAvailableBalanceInMinor())->toBe(100000);
    \expect($account->getAccountHolderName())->toBe('John Doe');
    \expect($account->getAccountIdentifiers()[0])->toBeInstanceOf(IbanDetailsInterface::class);
});

\it('retrieves a merchant account\'s transactions', function () {
    $client = \client([MerchantAccountResponse::account(), MerchantAccountResponse::transactions()]);

    $account = $client->getMerchantAccount('1');

    $from = DateTime::createFromFormat(DateTime::ATOM, '2025-05-01T00:00:00Z');
    $to = DateTime::createFromFormat(DateTime::ATOM, '2025-05-30T00:00:00Z');

    $transactions = $account->getTransactions($from, $to);

    \expect(count($transactions))->toBe(5);
    \expect($transactions[0])->toBeInstanceOf(\TrueLayer\Interfaces\MerchantAccount\Transactions\MerchantAccountPaymentInterface::class);
    \expect($transactions[1])->toBeInstanceOf(\TrueLayer\Interfaces\MerchantAccount\Transactions\MerchantAccountExternalPaymentInterface::class);
    \expect($transactions[2])->toBeInstanceOf(\TrueLayer\Interfaces\MerchantAccount\Transactions\MerchantAccountPendingPayoutInterface::class);
    \expect($transactions[3])->toBeInstanceOf(\TrueLayer\Interfaces\MerchantAccount\Transactions\MerchantAccountExecutedPayoutInterface::class);
    \expect($transactions[4])->toBeInstanceOf(\TrueLayer\Interfaces\MerchantAccount\Transactions\MerchantAccountRefundInterface::class);
});
