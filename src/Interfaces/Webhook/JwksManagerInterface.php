<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Webhook;

use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\SignerException;
use TrueLayer\Exceptions\ValidationException;

interface JwksManagerInterface
{
    /**
     * @return mixed[]
     */
    public function getJsonKeys(): array;

    /**
     * @throws ApiResponseUnsuccessfulException
     * @throws ApiRequestJsonSerializationException
     * @throws SignerException
     * @throws ValidationException
     */
    public function retrieve(): void;

    /**
     * @return bool
     */
    public function hasCache(): bool;

    /**
     * @return int|null
     */
    public function getRetrievedAt(): ?int;

    /**
     * @return bool
     */
    public function isExpired(): bool;

    /**
     * @return JwksManagerInterface
     */
    public function clear(): JwksManagerInterface;
}
