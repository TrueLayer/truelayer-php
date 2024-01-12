<?php

declare(strict_types=1);

namespace TrueLayer\Services\ApiClient\Decorators;

use Ramsey\Uuid\Provider\Node\RandomNodeProvider;
use Ramsey\Uuid\Uuid;
use TrueLayer\Constants\CustomHeaders;
use TrueLayer\Constants\ResponseStatusCodes;
use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Interfaces\ApiClient\ApiRequestInterface;
use TrueLayer\Services\Util\Retry;

final class IdempotencyKeyDecorator extends BaseApiClientDecorator
{
    public const MAX_RETRIES = 2;

    /**
     * @param ApiRequestInterface $apiRequest
     *
     * @throws ApiResponseUnsuccessfulException
     * @throws ApiRequestJsonSerializationException
     *
     * @return mixed
     */
    public function send(ApiRequestInterface $apiRequest)
    {
        $hasCustomIdempotencyKey = !empty($apiRequest->getHeaders()[CustomHeaders::IDEMPOTENCY_KEY]);

        if ($hasCustomIdempotencyKey || !$apiRequest->modifiesResources()) {
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

        $apiRequest->header(
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
