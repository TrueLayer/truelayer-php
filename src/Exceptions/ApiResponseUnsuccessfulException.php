<?php

declare(strict_types=1);

namespace TrueLayer\Exceptions;

class ApiResponseUnsuccessfulException extends \Exception
{
    /**
     * @var int
     */
    private int $statusCode;

    /**
     * @var array
     */
    private array $data = [];

    /**
     * @param int $statusCode
     * @param $data
     */
    public function __construct(int $statusCode, $data)
    {
        $this->statusCode = $statusCode;

        if (!is_array($data)) {
            parent::__construct((string) $data);
            return;
        }

        if (!empty($data['title'])) {
            parent::__construct((string) $data['title']);
        }

        $this->data = $data;
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->data['type'] ?? null;
    }

    /**
     * @return string|null
     */
    public function getDetail(): ?string
    {
        return $this->data['detail'] ?? null;
    }

    /**
     * @return string|null
     */
    public function getTraceId(): ?string
    {
        return $this->data['trace_id'] ?? null;
    }

    /**
     * @return array|null
     */
    public function getErrors(): ?array
    {
        return $this->data['errors'] ?? null;
    }
}
