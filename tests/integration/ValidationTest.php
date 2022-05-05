<?php

declare(strict_types=1);

use TrueLayer\Exceptions\ValidationException;

\it('has loaded validation message translations', function () {
    try {
        \client()->payment()->create();
    } catch (ValidationException $e) {
        $errors = $e->getErrors();
        expect($errors['amount_in_minor'][0])->toBe('The amount in minor field is required.');

        throw $e;
    }
})->throws(ValidationException::class);
