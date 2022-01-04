<?php

declare(strict_types=1);

namespace TrueLayer\Services\Api\Decorators;

use Closure;
use Illuminate\Contracts\Validation;
use Illuminate\Validation\ValidationException;
use TrueLayer\Contracts\Api\ApiClientInterface;
use TrueLayer\Contracts\Api\ApiRequestInterface;
use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiRequestValidationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\ApiResponseValidationException;

final class ValidationDecorator extends BaseApiClientDecorator
{
    private Validation\Factory $validatorFactory;

    /**
     * @param ApiClientInterface $next
     * @param Validation\Factory $validatorFactory
     */
    public function __construct(ApiClientInterface $next, Validation\Factory $validatorFactory)
    {
        parent::__construct($next);
        $this->validatorFactory = $validatorFactory;
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
        $payload = $apiRequest->getPayload();

        if ($rulesFactory = $apiRequest->getRequestRules()) {
            $payload = $this->validate($payload, $rulesFactory, ApiRequestValidationException::class);
            $apiRequest->payload($payload);
        }

        $data = $this->next->send($apiRequest);

        if ($rulesFactory = $apiRequest->getResponseRules()) {
            $data = $this->validate($data, $rulesFactory, ApiResponseValidationException::class);
        }

        return $data;
    }

    /**
     * @param array   $data
     * @param Closure $rulesFactory
     * @param string  $throwable
     *
     * @throws ApiResponseValidationException
     * @throws ApiRequestValidationException
     *
     * @return array
     */
    private function validate(array $data, Closure $rulesFactory, string $throwable): array
    {
        $validator = $this->validatorFactory->make($data, $rulesFactory($data));

        try {
            return $validator->validate();
        } catch (ValidationException $e) {
            throw new $throwable($validator);
        }
    }
}
