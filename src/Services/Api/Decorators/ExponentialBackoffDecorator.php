<?php

declare(strict_types=1);

namespace TrueLayer\Services\Api\Decorators;

use Psr\Http\Client\ClientExceptionInterface;
use TrueLayer\Constants\ResponseStatusCodes;
use TrueLayer\Contracts\Api\ApiRequestInterface;
use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Services\Util\Retry;

final class ExponentialBackoffDecorator extends BaseApiClientDecorator
{
    public const MAX_RETRIES = 4;

    /**
     * @param ApiRequestInterface $apiRequest
     *
     * @throws ApiRequestJsonSerializationException
     * @throws ApiResponseUnsuccessfulException
     * @throws ClientExceptionInterface
     *
     * @return mixed
     */
    public function send(ApiRequestInterface $apiRequest)
    {
        return Retry::max(self::MAX_RETRIES)
            ->when(fn ($e) => $this->doesErrorAllowRetry($e))
            ->start(fn () => $this->next->send($apiRequest));
    }

    /**
     * @param \Exception $e
     *
     * @return bool
     */
    private function doesErrorAllowRetry(\Exception $e): bool
    {
        // Allow retry on client errors, ex. caused by network issues
        if ($e instanceof ClientExceptionInterface) {
            return true;
        }

        // Allow retry on server errors or conflict errors
        return ($e instanceof ApiResponseUnsuccessfulException) && (
            $e->getStatusCode() >= ResponseStatusCodes::INTERNAL_SERVER_ERROR ||
            $e->getStatusCode() === ResponseStatusCodes::CONFLICT
        );
    }
}
