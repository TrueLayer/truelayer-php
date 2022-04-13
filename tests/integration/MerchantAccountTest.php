<?php

declare(strict_types=1);

use TrueLayer\Constants\AccountIdentifierTypes;
use TrueLayer\Interfaces\AccountIdentifier\IbanDetailsInterface;
use TrueLayer\Interfaces\AccountIdentifier\ScanDetailsInterface;
use TrueLayer\Tests\Integration\Mocks\MerchantAccountResponse;

\it('retrieves merchant accounts', function () {
    $accounts = \client(MerchantAccountResponse::accountsList())->getMerchantAccounts();

    \expect($accounts[0]->getId())->toBeString();
    \expect($accounts[0]->getCurrency())->toBe(\TrueLayer\Constants\Currencies::EUR);
    \expect($accounts[0]->getCurrentBalanceInMinor())->toBe(100000);
    \expect($accounts[0]->getAvailableBalanceInMinor())->toBe(100000);
    \expect($accounts[0]->getAccountHolderName())->toBe('John Doe');

    /** @var IbanDetailsInterface $accountIdentifier */
    $accountIdentifier = $accounts[0]->getAccountIdentifiers()[0];
    \expect($accountIdentifier)->toBeInstanceOf(IbanDetailsInterface::class);
    \expect($accountIdentifier->getIban())->toBe('GB38CLRB04066200003769');
    \expect($accountIdentifier->getType())->toBe(AccountIdentifierTypes::IBAN);

    \expect($accounts[1]->getId())->toBeString();
    \expect($accounts[1]->getCurrency())->toBe(\TrueLayer\Constants\Currencies::GBP);
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
    \expect($account->getCurrency())->toBe(\TrueLayer\Constants\Currencies::EUR);
    \expect($account->getCurrentBalanceInMinor())->toBe(100000);
    \expect($account->getAvailableBalanceInMinor())->toBe(100000);
    \expect($account->getAccountHolderName())->toBe('John Doe');
    \expect($account->getAccountIdentifiers()[0])->toBeInstanceOf(IbanDetailsInterface::class);
});
