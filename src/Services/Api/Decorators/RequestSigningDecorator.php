<?php

declare(strict_types=1);

namespace TrueLayer\Services\Api\Decorators;

use Ramsey\Uuid\Uuid;
use TrueLayer\Constants\RequestMethods;
use TrueLayer\Contracts\Api\ApiClientInterface;
use TrueLayer\Contracts\Api\ApiRequestInterface;
use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiRequestValidationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\ApiResponseValidationException;
use TrueLayer\Signing\Constants\CustomHeaders;
use TrueLayer\Signing\Contracts\Signer;

class RequestSigningDecorator extends BaseApiClientDecorator
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
     * @return array
     */
    public function send(ApiRequestInterface $apiRequest): array
    {
        if ($this->modifiesResources($apiRequest->getMethod())) {
            $key = Uuid::uuid1()->toString();
            $apiRequest->addHeader(CustomHeaders::IDEMPOTENCY_KEY, $key);

            $signature = $this->signer
                ->method($apiRequest->getMethod())
                ->path($apiRequest->getUri())
                ->body($apiRequest->getJsonPayload())
                ->headers([
                    CustomHeaders::IDEMPOTENCY_KEY => $key
                ])
                ->sign();

            $apiRequest->addHeader(CustomHeaders::SIGNATURE, $signature);
        }

        return $this->next->send($apiRequest);
    }

    /**
     * @param string $method
     *
     * @return bool
     */
    private function modifiesResources(string $method): bool
    {
        return \in_array($method, [
            RequestMethods::POST,
            RequestMethods::PUT,
            RequestMethods::PATCH,
            RequestMethods::DELETE,
        ]);
    }
}
