<?php

declare(strict_types=1);

namespace Test\Functional;

use DI\Container;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;
use Slim\Psr7\Factory\ServerRequestFactory;
use Throwable;

class WebTestCase extends TestCase
{
    /**
     * @param string $method
     * @param string $path
     * @param array  $body
     *
     * @return ServerRequestInterface
     * @throws Throwable
     */
    protected static function json(string $method, string $path, array $body): ServerRequestInterface
    {
        $request = self::request($method, $path)
            ->withHeader('Accept', 'application/json')
            ->withHeader('Content-Type', 'application/json');

        $request->getBody()->write(json_encode($body, JSON_THROW_ON_ERROR));

        return $request;
    }

    protected static function request(string $method, string $path): ServerRequestInterface
    {
        return (new ServerRequestFactory())->createServerRequest($method, $path);
    }

    protected function app(): App
    {
        /** @var Container $container */
        $container = require __DIR__ . '/../../config/container.php';

        /** @var App */
        return (require __DIR__ . '/../../config/app.php')($container);
    }
}
