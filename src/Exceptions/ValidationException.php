<?php

declare(strict_types=1);

namespace TrueLayer\Exceptions;

use Throwable;

class ValidationException extends \Exception
{
    /**
     * @var array
     */
    private array $errors;

    /**
     * @param array $errors
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(array $errors, $message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->errors = $errors;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
