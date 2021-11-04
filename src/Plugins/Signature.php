<?php

declare(strict_types=1);

namespace TrueLayer\Plugins;

use Http\Client\Common\Plugin;
use Http\Promise\Promise;
use Psr\Http\Message\RequestInterface;
use TrueLayer\Options;
use TrueLayer\Signing\Signer;

class Signature implements Plugin
{
    private Options $options;

    public function __construct(Options $options)
    {
        $this->options = $options;
    }

    public function handleRequest(RequestInterface $request, callable $next, callable $first): Promise
    {
        if ($request->getMethod() === 'POST') {
            $signer = Signer::signWithPem($this->options->getKid(), $this->options->getPrivateKey(), null);
            $signer->method($request->getMethod())
                ->path($request->getUri()->getPath())
                ->header('Idempotency-Key', $request->getHeader('Idempotency-Key')[0] ?? '')
                ->body((string)$request->getBody());
            $newRequest = $request->withHeader('Tl-Signature', $signer->sign());

            return $next($newRequest);
        }

        return next($request);
    }
}
