<?php

declare(strict_types=1);

namespace TrueLayer;

use Http\Client\Common\PluginClient;
use Http\Client\HttpClient;
use Http\Client\Common\Plugin;
use Http\Discovery\Psr18ClientDiscovery;
use Http\Message\Authentication\Bearer;
use TrueLayer\Plugins\Signature;

class HttpClientFactory
{
    public static function create(Options $options, AuthToken $token = null, $sign = false, array $plugins = [], HttpClient $client = null): HttpClient
    {
        if (!$client) {
            $client = Psr18ClientDiscovery::find();
        }

        $plugins[] = new Plugin\ContentTypePlugin([]);

        if ($sign) {
            $plugins[] = new Signature($options);
        }

        if ($token) {
            $authentication = new Bearer($token);
            $plugins[] = new Plugin\AuthenticationPlugin($authentication);
        }

        return new PluginClient($client, $plugins);
    }
}
