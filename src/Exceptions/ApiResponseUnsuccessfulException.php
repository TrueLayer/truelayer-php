<?php

declare(strict_types=1);

namespace TrueLayer\Exceptions;

class ApiResponseUnsuccessfulException extends Exception
{
    /**
     * @var int
     */
    private int $statusCode;

    /**
     * @var string
     */
    private string $type;

    /**
     * @var string
     */
    private string $detail;

    /**
     * @var string
     */
    private string $traceId;

    /**
     * @var mixed[]
     */
    private array $errors;

    /**
     * @param int   $statusCode
     * @param mixed $data
     */
    public function __construct(int $statusCode, $data)
    {
        $this->statusCode = $statusCode;

        if (!\is_array($data)) {
            return;
        }

        if (!empty($data['type']) && \is_string($data['type'])) {
            $this->type = $data['type'];
        }

        if (!empty($data['detail']) && \is_string($data['detail'])) {
            $this->detail = $data['detail'];
        }

        if (!empty($data['trace_id']) && \is_string($data['trace_id'])) {
            $this->traceId = $data['trace_id'];
        }

        if (!empty($data['errors']) && \is_array($data['errors'])) {
            $this->errors = $data['errors'];
        }

        if (!empty($data['title']) && \is_string($data['title'])) {
            parent::__construct($data['title']);
        }
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
        return $this->type ?? null;
    }

    /**
     * @return string|null
     */
    public function getDetail(): ?string
    {
        return $this->detail ?? null;
    }

    /**
     * @return string|null
     */
    public function getTraceId(): ?string
    {
        return $this->traceId ?? null;
    }

    /**
     * @return mixed[]|null
     */
    public function getErrors(): ?array
    {
        return $this->errors ?? null;
    }
}
