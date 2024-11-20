<?php

declare(strict_types=1);

namespace TrueLayer\Entities\SignupPlus;

use TrueLayer\Entities\Entity;
use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Exceptions\SignerException;
use TrueLayer\Interfaces\HasApiFactoryInterface;
use TrueLayer\Interfaces\SignupPlus\SignupPlusUserDataRequestInterface;
use TrueLayer\Interfaces\SignupPlus\SignupPlusUserDataRetrievedInterface;
use TrueLayer\Traits\ProvidesApiFactory;

class SignupPlusUserDataRequest extends Entity implements SignupPlusUserDataRequestInterface, HasApiFactoryInterface
{
    use ProvidesApiFactory;

    /**
     * @var string
     */
    protected string $paymentId;

    /**
     * @var string
     */
    protected string $mandateId;

    /**
     * @param string $paymentId
     *
     * @return SignupPlusUserDataRequestInterface
     */
    public function paymentId(string $paymentId): SignupPlusUserDataRequestInterface
    {
        $this->paymentId = $paymentId;

        return $this;
    }

    /**
     * @param string $mandateId
     *
     * @return SignupPlusUserDataRequestInterface
     */
    public function mandateId(string $mandateId): SignupPlusUserDataRequestInterface
    {
        $this->mandateId = $mandateId;

        return $this;
    }

    /**
     * @throws ApiRequestJsonSerializationException
     * @throws ApiResponseUnsuccessfulException
     * @throws InvalidArgumentException
     * @throws SignerException
     *
     * @return SignupPlusUserDataRetrievedInterface
     */
    public function retrieve(): SignupPlusUserDataRetrievedInterface
    {
        if (empty($this->paymentId) && empty($this->mandateId)) {
            throw new ApiRequestJsonSerializationException('You need to pass a payment or mandate id');
        }

        $data = !empty($this->paymentId)
            ? $this->getApiFactory()->signupPlusApi()->retrieveUserDataByPaymentId($this->paymentId)
            : $this->getApiFactory()->signupPlusApi()->retrieveUserDataByMandateId($this->mandateId);

        return $this->make(SignupPlusUserDataRetrievedInterface::class, $data);
    }
}
