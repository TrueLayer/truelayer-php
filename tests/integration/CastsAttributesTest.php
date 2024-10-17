<?php

declare(strict_types=1);

use TrueLayer\Constants\DateTime;
use TrueLayer\Traits\CastsAttributes;

$trait = new class {
    use CastsAttributes;

    protected function make(string $abstract, ?array $data = null)
    {
    }

    public function test(?string $dateTime = null)
    {
        return $this->toDateTime($dateTime);
    }
};

\it('parses datetime strings', function (string $input, string $expectedOutput) use ($trait) {
    $dateTime = $trait->test($input);
    \expect($dateTime)->toBeInstanceOf(DateTimeInterface::class);
    \expect($dateTime->format(DateTime::FORMAT))->toBe($expectedOutput);
})->with([
    'seconds' => ['2023-02-04T13:40:23Z', '2023-02-04T13:40:23.000000Z'],
    'miliseconds' => ['2023-02-04T13:40:23.798Z', '2023-02-04T13:40:23.798000Z'],
    'microseconds' => ['2023-02-04T13:40:23.798400Z', '2023-02-04T13:40:23.798400Z'],
    'nanoseconds' => ['2023-02-04T13:40:23.798400015Z', '2023-02-04T13:40:23.798400Z'],
]);

\it('returns null for invalid datetimes', function (string $dateTime) use ($trait) {
    \expect($trait->test($dateTime))->toBeNull();
})->with([
    'empty string' => '',
    'invalid datetime' => 'ABC',
]);
