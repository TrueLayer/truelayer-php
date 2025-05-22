<?php

declare(strict_types=1);

use TrueLayer\Interfaces\AccountIdentifier\AccountIdentifierInterface;
use TrueLayer\Interfaces\MerchantAccount\MerchantAccountInterface;

\it('retrieves merchant accounts', function () {
    $accounts = \client()->getMerchantAccounts();

    \expect($accounts)->toBeArray();
    \expect(\count($accounts))->toBeGreaterThan(0);
    \expect($accounts[0])->toBeInstanceOf(MerchantAccountInterface::class);
    \expect($accounts[0]->getId())->toBeString();
    \expect($accounts[0]->getCurrency())->toBeString();
    \expect($accounts[0]->getAccountHolderName())->toBeString();
    \expect($accounts[0]->getAvailableBalanceInMinor())->toBeInt();
    \expect($accounts[0]->getCurrentBalanceInMinor())->toBeInt();
    \expect($accounts[0]->getAccountIdentifiers())->toBeArray();
    \expect(\count($accounts[0]->getAccountIdentifiers()))->toBeGreaterThan(0);
    \expect($accounts[0]->getAccountIdentifiers()[0])->toBeInstanceOf(AccountIdentifierInterface::class);
});

\it('retrieves merchant account', function () {
    $accounts = \client()->getMerchantAccounts();
    $account = \client()->getMerchantAccount($accounts[0]->getId());

    \expect($account)->toBeInstanceOf(MerchantAccountInterface::class);
    \expect($account->getId())->toBeString();
    \expect($account->getCurrency())->toBeString();
    \expect($account->getAccountHolderName())->toBeString();
    \expect($account->getAvailableBalanceInMinor())->toBeInt();
    \expect($account->getCurrentBalanceInMinor())->toBeInt();
    \expect($account->getAccountIdentifiers())->toBeArray();
    \expect(\count($account->getAccountIdentifiers()))->toBeGreaterThan(0);
    \expect($account->getAccountIdentifiers()[0])->toBeInstanceOf(AccountIdentifierInterface::class);
});

\it('retrieves merchant account transactions', function () {
    $accounts = \client()->getMerchantAccounts();
    $account = \client()->getMerchantAccount($accounts[0]->getId());

    $from = (new DateTime())->sub(new DateInterval("PT5H"));
    $to = new DateTime();

    $transactions = $account->getTransactions($from, $to);
    \expect($transactions)->toBeArray();
    \expect(count($transactions))->toBeGreaterThan(0);
    \expect($transactions[0])->toBeInstanceOf(\TrueLayer\Interfaces\MerchantAccount\Transactions\MerchantAccountTransactionRetrievedInterface::class);
});
