<?php

declare(strict_types=1);

namespace TrueLayer\Entities\SignupPlus;

use TrueLayer\Entities\Entity;
use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Exceptions\SignerException;
use TrueLayer\Interfaces\HasApiFactoryInterface;
use TrueLayer\Interfaces\SignupPlus\SignupPlusAuthUriCreatedInterface;
use TrueLayer\Interfaces\SignupPlus\SignupPlusAuthUriRequestInterface;
use TrueLayer\Traits\ProvidesApiFactory;

class SignupPlusAuthUriRequest extends Entity implements SignupPlusAuthUriRequestInterface, HasApiFactoryInterface
{
    use ProvidesApiFactory;

    /**
     * @var string
     */
    protected string $paymentId;

    /**
     * @var string
     */
    protected string $state;

    /**
     * @var string[]
     */
    protected array $arrayFields = [
        'payment_id',
        'state',
    ];

    /**
     * @param string $paymentId
     *
     * @return SignupPlusAuthUriRequestInterface
     */
    public function paymentId(string $paymentId): SignupPlusAuthUriRequestInterface
    {
        $this->paymentId = $paymentId;

        return $this;
    }

    /**
     * @param string $state
     *
     * @return SignupPlusAuthUriRequestInterface
     */
    public function state(string $state): SignupPlusAuthUriRequestInterface
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @throws ApiResponseUnsuccessfulException
     * @throws ApiRequestJsonSerializationException
     * @throws SignerException
     * @throws InvalidArgumentException
     *
     * @return SignupPlusAuthUriCreatedInterface
     */
    public function create(): SignupPlusAuthUriCreatedInterface
    {
        $data = $this->getApiFactory()->signupPlusApi()->createAuthUri(
            $this->toArray(),
        );

        return $this->make(SignupPlusAuthUriCreatedInterface::class, $data);
    }
}
