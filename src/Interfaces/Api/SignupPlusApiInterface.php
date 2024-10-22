<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Api;

use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\SignerException;

interface SignupPlusApiInterface
{
    /**
     * @param mixed[] $authUriRequest
     *
     * @throws ApiResponseUnsuccessfulException
     * @throws SignerException
     * @throws ApiRequestJsonSerializationException
     *
     * @return mixed[]
     */
    public function createAuthUri(array $authUriRequest): array;
}
