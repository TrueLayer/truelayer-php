<?php

declare(strict_types=1);

namespace TrueLayer\Traits;

use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use TrueLayer\Exceptions\MissingHttpImplementationException;
use TrueLayer\Interfaces\Configuration\ConfigInterface;

trait HttpClient
{
    /**
     * @param ConfigInterface $config
     *
     * @throws MissingHttpImplementationException
     *
     * @return ClientInterface
     */
    private function discoverHttpClient(ConfigInterface $config): ClientInterface
    {
        try {
            return $config->getHttpClient() ?? Psr18ClientDiscovery::find();
        } catch (\Exception $exception) {
            throw new MissingHttpImplementationException('Could not discover a PSR-18 HTTP client implementation', 0, $exception);
        }
    }

    /**
     * @param ConfigInterface $config
     *
     * @throws MissingHttpImplementationException
     *
     * @return RequestFactoryInterface
     */
    private function discoverHttpRequestFactory(ConfigInterface $config): RequestFactoryInterface
    {
        try {
            return $config->getHttpRequestFactory() ?? Psr17FactoryDiscovery::findRequestFactory();
        } catch (\Exception $exception) {
            throw new MissingHttpImplementationException('Could not discover a PSR-17 HTTP request factory implementation', 0, $exception);
        }
    }
}
