<?php

declare(strict_types=1);

use TrueLayer\Services\Util\Retry;

\it('executes closure once if successful and returns', function () {
    $ran = 0;

    $return = Retry::max(5)->start(function () use (&$ran) {
        ++$ran;

        return 'OK';
    });

    \expect($ran)->toBe(1);
    \expect($return)->toBe('OK');
});

\it('retries up to max', function () {
    $ran = 0;

    Retry::max(5)->start(function () use (&$ran) {
        ++$ran;
        throw new \Exception();
    });

    // One main execution, plus 5 retries
    \expect($ran)->toBe(6);
})->throws(\Exception::class);

\it('retries until successful', function () {
    $ran = 0;

    $return = Retry::max(5)->start(function () use (&$ran) {
        ++$ran;

        if ($ran <= 2) {
            throw new \Exception();
        }

        return 'OK';
    });

    \expect($ran)->toBe(3);
    \expect($return)->toBe('OK');
});

\it('does not retry when when() returns false', function () {
    $ran = 0;

    Retry::max(5)
        ->when(fn () => false)
        ->start(function () use (&$ran) {
            ++$ran;
            throw new \Exception();
        });

    \expect($ran)->toBe(1);
})->throws(\Exception::class);

\it('does retry when when() returns true', function () {
    $ran = 0;

    Retry::max(5)
        ->when(fn () => false)
        ->start(function () use (&$ran) {
            ++$ran;
            throw new \Exception();
        });

    \expect($ran)->toBe(6);
})->throws(\Exception::class);

\it('retries with exponential backoff delays', function () {
    global $sleeps;

    $min = [1000000, 2000000, 4000000, 8000000];
    $max = [2000000, 3000000, 5000000, 9000000];

    Retry::max(5)->start(function () {
        throw new \Exception();
    });

    \expect($sleeps[0] >= $min[0] && $sleeps[0] <= $max[0])->toBeTrue();
    \expect($sleeps[1] >= $min[1] && $sleeps[1] <= $max[1])->toBeTrue();
    \expect($sleeps[2] >= $min[2] && $sleeps[2] <= $max[2])->toBeTrue();
    \expect($sleeps[3] >= $min[3] && $sleeps[3] <= $max[3])->toBeTrue();
})->throws(Exception::class);
