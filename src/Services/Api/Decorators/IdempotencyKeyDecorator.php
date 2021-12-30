<?php

declare(strict_types=1);

namespace TrueLayer\Services\Api\Decorators;

use Ramsey\Uuid\Provider\Node\RandomNodeProvider;
use Ramsey\Uuid\Uuid;
use TrueLayer\Constants\ResponseStatusCodes;
use TrueLayer\Contracts\Api\ApiRequestInterface;
use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiRequestValidationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\ApiResponseValidationException;
use TrueLayer\Services\Util\Retry;
use TrueLayer\Signing\Constants\CustomHeaders;

class IdempotencyKeyDecorator extends BaseApiClientDecorator
{
    public const MAX_RETRIES = 2;

    /**
     * @param ApiRequestInterface $apiRequest
     *
     * @throws ApiRequestJsonSerializationException
     * @throws ApiRequestValidationException
     * @throws ApiResponseUnsuccessfulException
     * @throws ApiResponseValidationException
     *
     * @return mixed
     */
    public function send(ApiRequestInterface $apiRequest)
    {
        if (!$apiRequest->modifiesResources()) {
            return $this->next->send($apiRequest);
        }

        return Retry::max(self::MAX_RETRIES)
            ->when(fn ($e) => $this->isIdempotencyKeyReuseError($e))
            ->start(function () use ($apiRequest) {
                $this->addHeaders($apiRequest);

                return $this->next->send($apiRequest);
            });
    }

    /**
     * @param ApiRequestInterface $apiRequest
     */
    private function addHeaders(ApiRequestInterface $apiRequest): void
    {
        $nodeProvider = new RandomNodeProvider();

        $apiRequest->addHeader(
            CustomHeaders::IDEMPOTENCY_KEY,
            Uuid::uuid1($nodeProvider->getNode())->toString()
        );
    }

    /**
     * @param \Exception $e
     *
     * @return bool
     */
    private function isIdempotencyKeyReuseError(\Exception $e): bool
    {
        return ($e instanceof ApiResponseUnsuccessfulException) &&
            $e->getStatusCode() === ResponseStatusCodes::UNPROCESSABLE_ENTITY &&
            $e->getMessage() === 'Idempotency-Key Reuse';
    }
}
