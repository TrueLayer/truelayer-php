<?php

namespace TrueLayer;

use Http\Client\Common\PluginClient;
use Http\Client\HttpClient;
use Http\Client\Common\Plugin;
use Http\Discovery\Psr18ClientDiscovery;

class HttpClientFactory
{
    public static function create(array $plugins = [], HttpClient $client = null): HttpClient
    {
        if (!$client) {
            $client = Psr18ClientDiscovery::find();
        }
        $plugins[] = new Plugin\ContentTypePlugin([]);
        return new PluginClient($client, $plugins);
    }
}
