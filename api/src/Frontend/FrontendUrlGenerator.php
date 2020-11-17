<?php

declare(strict_types=1);

namespace App\Frontend;

use function http_build_query;

class FrontendUrlGenerator
{
    private string $frontendUrl;

    public function __construct(string $frontendUrl)
    {
        $this->frontendUrl = $frontendUrl;
    }

    public function generate(string $uri, array $params = []): string
    {
        return $this->frontendUrl
            . ($uri ? '/' . $uri : '')
            . ($params ? '?' . http_build_query($params) : '');
    }
}
