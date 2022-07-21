<?php

declare(strict_types=1);

namespace TrueLayer\Services\Util;

final class FromGlobals
{
    /**
     * @return string
     */
    public static function getPath(): string
    {
        return $_SERVER['REQUEST_URI'] ?? '';
    }

    /**
     * @return string
     */
    public static function getBody(): string
    {
        $raw = \file_get_contents('php://input');
        if (!empty($raw)) {
            return $raw;
        }

        $post = \json_encode($_POST);
        if (!empty($post)) {
            return $post;
        }

        return '';
    }

    /**
     * @return array<string, string>
     */
    public static function getHeaders(): array
    {
        $headers = [];

        foreach ($_SERVER as $key => $value) {
            if (\substr($key, 0, 5) === 'HTTP_') {
                $headers[\substr(\str_replace('_', '-', $key), 5)] = $value;
            } elseif (\in_array($key, ['CONTENT_TYPE', 'CONTENT_LENGTH', 'CONTENT_MD5'], true)) {
                $headers[\str_replace('_', '-', $key)] = $value;
            }
        }

        return $headers;
    }
}
