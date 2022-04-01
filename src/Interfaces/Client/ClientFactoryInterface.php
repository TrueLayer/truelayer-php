<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Client;

use TrueLayer\Exceptions\SignerException;

interface ClientFactoryInterface
{
    /**
     * @param ConfigInterface $config
     *
     * @throws SignerException
     *
     * @return ClientInterface
     */
    public function make(ConfigInterface $config): ClientInterface;
}
