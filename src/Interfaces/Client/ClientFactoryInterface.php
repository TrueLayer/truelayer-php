<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Client;

use TrueLayer\Exceptions\MissingHttpImplementationException;
use TrueLayer\Exceptions\SignerException;
use TrueLayer\Interfaces\Configuration\ClientConfigInterface;

interface ClientFactoryInterface
{
    /**
     * @param ClientConfigInterface $config
     *
     * @throws MissingHttpImplementationException
     * @throws SignerException
     *
     * @return ClientInterface
     */
    public function make(ClientConfigInterface $config): ClientInterface;
}
