<?php

declare(strict_types=1);

namespace TrueLayer\Services\Api\Decorators;

use Psr\Http\Client\ClientExceptionInterface;
use TrueLayer\Contracts\Api\ApiRequestInterface;
use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiRequestValidationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\ApiResponseValidationException;

class ExponentialBackoffDecorator extends BaseApiClientDecorator
{
    const MAX_RETRIES = 5;

    /**
     * @param ApiRequestInterface $apiRequest
     * @return array
     * @throws ApiRequestJsonSerializationException
     * @throws ApiRequestValidationException
     * @throws ApiResponseUnsuccessfulException
     * @throws ApiResponseValidationException
     * @throws ClientExceptionInterface
     */
    public function send(ApiRequestInterface $apiRequest): array
    {
        return $this->try($apiRequest);
    }

    /**
     * @param ApiRequestInterface $apiRequest
     * @param int $attempt
     * @return array
     * @throws ApiRequestJsonSerializationException
     * @throws ApiRequestValidationException
     * @throws ApiResponseUnsuccessfulException
     * @throws ApiResponseValidationException
     * @throws ClientExceptionInterface
     */
    private function try(ApiRequestInterface $apiRequest, int $attempt = 0): array
    {
        var_dump('trying ' . $attempt . ' ' . $apiRequest->getUri());

        try {
            return $this->next->send($apiRequest);
        } catch (\Exception $e) {
            $this->checkAllowRetry($e, $attempt);
            $this->delay($attempt);
            return $this->try($apiRequest, $attempt + 1);
        }
    }

    /**
     * @param \Exception $e
     * @param int $attempt
     * @throws \Exception
     */
    private function checkAllowRetry(\Exception $e, int $attempt): void
    {

        $isServerError =  ($e instanceof ApiResponseUnsuccessfulException) &&
            $e->getStatusCode() >= 500;

        $isClientError = $e instanceof ClientExceptionInterface;

        $isRecoverable = $isClientError || $isServerError;

        if (!$isRecoverable || $attempt >= self::MAX_RETRIES) {
            throw $e;
        }
    }

    /**
     * @param int $attempt
     */
    private function delay(int $attempt): void
    {
        $delay = mt_rand(0, 1000000) + (pow(2, $attempt) * 1000000);
        var_dump('delaying '. $delay);
        usleep($delay);
    }
}
