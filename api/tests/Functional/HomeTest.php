<?php

declare(strict_types=1);

namespace Test\Functional;

class HomeTest extends WebTestCase
{
    public function testMethodPostReturnStatus405(): void
    {
        $response = $this->app()->handle(
            self::json('POST', '/')->withHeader('X-Features', '!NEW_HOME')
        );

        $this->assertEquals(405, $response->getStatusCode());
    }

    public function testSuccess(): void
    {
        $response = $this->app()->handle(
            self::json('GET', '/')->withHeader('X-Features', '!NEW_HOME')
        );

        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        $this->assertEquals('[]', (string)$response->getBody());
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testNew(): void
    {
        $response = $this->app()->handle(
            self::json('GET', '/')->withHeader('X-Features', 'NEW_HOME')
        );

        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        $this->assertEquals('{"name":"API"}', (string)$response->getBody());
        $this->assertEquals(200, $response->getStatusCode());
    }
}
