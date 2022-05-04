<?php

declare(strict_types=1);

namespace TrueLayer\Exceptions;

use TrueLayer\Constants\CustomHeaders;

class ApiResponseUnsuccessfulException extends Exception
{
    /**
     * @var string
     */
    private string $title = 'server_error';

    /**
     * @var int
     */
    private int $statusCode;

    /**
     * @var string
     */
    private string $type = 'https://docs.truelayer.com/docs/error-types';

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
     * @param int        $statusCode
     * @param mixed      $data
     * @param string[][] $headers
     */
    public function __construct(int $statusCode, $data, array $headers = [])
    {
        $this->statusCode = $statusCode;

        if (!$this->setFieldsFromProblemDetailsFormat($data)) {
            $this->setFieldsFromLegacyFormat($data, $headers);
        }

        parent::__construct($this->title);
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
    public function getTitle(): ?string
    {
        return $this->title ?? null;
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

    /**
     * @param mixed $data
     *
     * @return bool
     */
    private function setFieldsFromProblemDetailsFormat($data): bool
    {
        if (!\is_array($data) || empty($data['type']) || empty($data['title']) || empty($data['status']) || empty($data['trace_id'])) {
            return false;
        }

        if (\is_string($data['type'])) {
            $this->type = $data['type'];
        }

        if (\is_string($data['title'])) {
            $this->title = $data['title'];
        }

        if (\is_string($data['trace_id'])) {
            $this->traceId = $data['trace_id'];
        }

        if (!empty($data['detail']) && \is_string($data['detail'])) {
            $this->detail = $data['detail'];
        }

        if (!empty($data['errors']) && \is_array($data['errors'])) {
            $this->errors = $data['errors'];
        }

        return true;
    }

    /**
     * @param mixed      $data
     * @param string[][] $headers
     */
    private function setFieldsFromLegacyFormat($data, array $headers = []): void
    {
        if (!empty($headers[CustomHeaders::CORRELATION_ID][0])) {
            $traceId = $headers[CustomHeaders::CORRELATION_ID][0];
            if (\is_string($traceId)) {
                $this->traceId = $traceId;
            }
        }

        if (\is_array($data) && \is_string($data['error'])) {
            $this->title = $data['error'];
        }
    }
}
