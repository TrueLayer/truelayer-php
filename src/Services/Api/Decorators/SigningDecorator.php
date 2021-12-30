<?php

declare(strict_types=1);

namespace TrueLayer\Services\Api\Decorators;

use TrueLayer\Contracts\Api\ApiClientInterface;
use TrueLayer\Contracts\Api\ApiRequestInterface;
use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiRequestValidationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\ApiResponseValidationException;
use TrueLayer\Signing\Constants\CustomHeaders;
use TrueLayer\Signing\Contracts\Signer;

class SigningDecorator extends BaseApiClientDecorator
{
    /**
     * @var Signer
     */
    private Signer $signer;

    /**
     * @param ApiClientInterface $next
     * @param Signer             $signer
     */
    public function __construct(ApiClientInterface $next, Signer $signer)
    {
        parent::__construct($next);
        $this->signer = $signer;
    }

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
        if ($apiRequest->modifiesResources()) {
            $this->addHeaders($apiRequest);
        }

        return $this->next->send($apiRequest);
    }

    /**
     * @param ApiRequestInterface $apiRequest
     *
     * @throws ApiRequestJsonSerializationException
     */
    private function addHeaders(ApiRequestInterface $apiRequest): void
    {
        $idempotencyKey = $apiRequest->getHeaders()[CustomHeaders::IDEMPOTENCY_KEY];

        $signature = $this->signer
            ->method($apiRequest->getMethod())
            ->path($apiRequest->getUri())
            ->body($apiRequest->getJsonPayload())
            ->headers([
                CustomHeaders::IDEMPOTENCY_KEY => $idempotencyKey,
            ])
            ->sign();

        $apiRequest->addHeader(CustomHeaders::SIGNATURE, $signature);
    }
}
