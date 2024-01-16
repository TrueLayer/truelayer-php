<?php

declare(strict_types=1);

use TrueLayer\Constants\AuthorizationFlowActionTypes;
use TrueLayer\Constants\AuthorizationFlowStatusTypes;
use TrueLayer\Constants\FormInputTypes;
use TrueLayer\Constants\PaymentStatus;
use TrueLayer\Interfaces\Payment\AuthorizationFlow\Action\RedirectActionInterface;
use TrueLayer\Interfaces\Payment\AuthorizationFlow\AuthorizationFlowAuthorizationFailedInterface;
use TrueLayer\Interfaces\Payment\AuthorizationFlow\AuthorizationFlowAuthorizingInterface;
use TrueLayer\Tests\Integration\Mocks\StartAuthorizationFlowResponse;

\it('sends return uri', function () {
    \client(StartAuthorizationFlowResponse::success())->paymentAuthorizationFlow('123')
        ->returnUri('https://foo.bar')
        ->start();

    \expect(\getRequestPayload(1, false))->toBe('{"user_account_selection":null,"provider_selection":null,"scheme_selection":null,"redirect":{"return_uri":"https:\/\/foo.bar","direct_return_uri":null},"form":{"input_types":[]}}');
});

\it('sends direct return uri', function () {
    \client(StartAuthorizationFlowResponse::success())->paymentAuthorizationFlow('123')
        ->returnUri('https://foo.bar')
        ->directReturnUri('https://foo.baz')
        ->start();

    \expect(\getRequestPayload(1, false))->toBe('{"user_account_selection":null,"provider_selection":null,"scheme_selection":null,"redirect":{"return_uri":"https:\/\/foo.bar","direct_return_uri":"https:\/\/foo.baz"},"form":{"input_types":[]}}');
});

\it('sends provider selection', function () {
    \client(StartAuthorizationFlowResponse::success())->paymentAuthorizationFlow('123')
        ->returnUri('https://foo.bar')
        ->enableProviderSelection()
        ->start();

    \expect(\getRequestPayload(1, false))->toBe('{"user_account_selection":null,"provider_selection":{},"scheme_selection":null,"redirect":{"return_uri":"https:\/\/foo.bar","direct_return_uri":null},"form":{"input_types":[]}}');
});

\it('sends scheme selection', function () {
    \client(StartAuthorizationFlowResponse::success())->paymentAuthorizationFlow('123')
        ->returnUri('https://foo.bar')
        ->enableSchemeSelection()
        ->start();

    \expect(\getRequestPayload(1, false))->toBe('{"user_account_selection":null,"provider_selection":null,"scheme_selection":{},"redirect":{"return_uri":"https:\/\/foo.bar","direct_return_uri":null},"form":{"input_types":[]}}');
});

\it('sends user account selection', function () {
    \client(StartAuthorizationFlowResponse::success())->paymentAuthorizationFlow('123')
        ->returnUri('https://foo.bar')
        ->enableUserAccountSelection()
        ->start();

    \expect(\getRequestPayload(1, false))->toBe('{"user_account_selection":{},"provider_selection":null,"scheme_selection":null,"redirect":{"return_uri":"https:\/\/foo.bar","direct_return_uri":null},"form":{"input_types":[]}}');
});

\it('sends form inputs', function () {
    \client(StartAuthorizationFlowResponse::success())->paymentAuthorizationFlow('123')
        ->returnUri('https://foo.bar')
        ->formInputTypes([FormInputTypes::SELECT, FormInputTypes::TEXT, FormInputTypes::TEXT_WITH_IMAGE])
        ->start();

    \expect(\getRequestPayload(1, false))->toBe('{"user_account_selection":null,"provider_selection":null,"scheme_selection":null,"redirect":{"return_uri":"https:\/\/foo.bar","direct_return_uri":null},"form":{"input_types":["select","text","text_with_image"]}}');
});

\it('sends hpp capabilities', function () {
    \client(StartAuthorizationFlowResponse::success())->paymentAuthorizationFlow('123')
        ->returnUri('https://foo.bar')
        ->useHPPCapabilities()
        ->start();

    \expect(\getRequestPayload(1, false))->toBe('{"user_account_selection":null,"provider_selection":{},"scheme_selection":{},"redirect":{"return_uri":"https:\/\/foo.bar","direct_return_uri":null},"form":{"input_types":["text","text_with_image","select"]}}');
});

\it('handles authorizing response', function () {
    /** @var AuthorizationFlowAuthorizingInterface $result */
    $result = \client(StartAuthorizationFlowResponse::success())->paymentAuthorizationFlow('123')
        ->returnUri('https://foo.bar')
        ->start();

    \expect($result)->toBeInstanceOf(AuthorizationFlowAuthorizingInterface::class);
    \expect($result->getStatus())->toBe(AuthorizationFlowStatusTypes::AUTHORIZING);

    /** @var RedirectActionInterface $nextAction */
    $nextAction = $result->getNextAction();
    \expect($nextAction)->toBeInstanceOf(RedirectActionInterface::class);
    \expect($nextAction->getUri())->toBe('https://pay-mock-connect.t7r.dev/login/a7d5f4a5-f2d7-464a-af26-22f6f417d0e9#token=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJqdGkiOiJhN2Q1ZjRhNS1mMmQ3LTQ2NGEtYWYyNi0yMmY2ZjQxN2QwZTkiLCJzY29wZSI6InBheS1tb2NrLWNvbm5lY3QtYXBpIiwibmJmIjoxNjQzOTgzNzM3LCJleHAiOjE2NDM5ODczMzcsImlzcyI6Imh0dHBzOi8vcGF5LW1vY2stY29ubmVjdC50N3IuZGV2IiwiYXVkIjoiaHR0cHM6Ly9wYXktbW9jay1jb25uZWN0LnQ3ci5kZXYifQ.l_qafIgtWJGxZcsQNOeASYa9xeKij2GBbbKBpQKET98');
    \expect($nextAction->getType())->toBe(AuthorizationFlowActionTypes::REDIRECT);
});

\it('handles failed response', function () {
    /** @var AuthorizationFlowAuthorizationFailedInterface $result */
    $result = \client(StartAuthorizationFlowResponse::failed())->paymentAuthorizationFlow('123')
        ->returnUri('https://foo.bar')
        ->start();

    \expect($result)->toBeInstanceOf(AuthorizationFlowAuthorizationFailedInterface::class);
    \expect($result->getStatus())->toBe(AuthorizationFlowStatusTypes::FAILED);
    \expect($result->getFailureStage())->toBe(PaymentStatus::AUTHORIZING);
    \expect($result->getFailureReason())->toBe('provider_rejected');
});
