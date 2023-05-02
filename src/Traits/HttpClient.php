<?php

declare(strict_types=1);

namespace TrueLayer\Traits;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use PsrDiscovery\Discover;
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
        if ($client = $config->getHttpClient()) {
            return $client;
        }

        try {
            $client = Discover::httpClient();
        } catch (\Exception $exception) {
            throw new MissingHttpImplementationException('Could not discover a PSR-18 HTTP client implementation', 0, $exception);
        }

        if (!($client instanceof ClientInterface)) {
            throw new MissingHttpImplementationException('Please provide a PSR-18 HTTP client implementation such as Guzzle');
        }

        return $client;
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
        if ($factory = $config->getHttpRequestFactory()) {
            return $factory;
        }

        try {
            $factory = Discover::httpRequestFactory();
        } catch (\Exception $exception) {
            throw new MissingHttpImplementationException('Could not discover a PSR-17 HTTP request factory implementation', 0, $exception);
        }

        if (!($factory instanceof RequestFactoryInterface)) {
            throw new MissingHttpImplementationException('Please provide a PSR-17 HTTP request factory implementation such as Guzzle');
        }

        return $factory;
    }
}
