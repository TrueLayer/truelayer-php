<?php

declare(strict_types=1);

namespace TrueLayer\Services\ApiClient\Decorators;

use TrueLayer\Constants\CustomHeaders;
use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\SignerException;
use TrueLayer\Interfaces\ApiClient\ApiClientInterface;
use TrueLayer\Interfaces\ApiClient\ApiRequestInterface;
use TrueLayer\Signing\Contracts\Signer;

final class SigningDecorator extends BaseApiClientDecorator
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
     * @throws ApiResponseUnsuccessfulException
     * @throws SignerException
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
     * @throws SignerException
     */
    private function addHeaders(ApiRequestInterface $apiRequest): void
    {
        $idempotencyKey = $apiRequest->getHeaders()[CustomHeaders::IDEMPOTENCY_KEY];

        $signer = $this->signer
            ->method($apiRequest->getMethod())
            ->path($apiRequest->getUri())
            ->body($apiRequest->getJsonPayload())
            ->headers([
                CustomHeaders::IDEMPOTENCY_KEY => $idempotencyKey,
            ]);

        try {
            $signature = $signer->sign();
        } catch (\Exception $e) {
            throw new SignerException($e->getMessage(), $e->getCode(), $e);
        }

        $apiRequest->header(CustomHeaders::SIGNATURE, $signature);
    }
}
