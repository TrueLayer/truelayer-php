<?php

declare(strict_types=1);

namespace TrueLayer\Services\Api;

use TrueLayer\Constants\Endpoints;
use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\SignerException;
use TrueLayer\Interfaces\Api\SignupPlusApiInterface;

class SignupPlusApi extends Api implements SignupPlusApiInterface
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
    public function createAuthUri(array $authUriRequest): array
    {
        return (array) $this->request()
            ->uri(Endpoints::SIGNUP_PLUS_AUTH)
            ->payload($authUriRequest)
            ->post();
    }
}
