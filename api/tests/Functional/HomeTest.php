<?php

declare(strict_types=1);

namespace Test\Functional;

use App\Http\Action\HomeAction;

class HomeTest extends WebTestCase
{
    /**
     * @covers HomeAction::handle
     */
    public function testMethodPostReturnStatus405(): void
    {
        $response = $this->app()->handle(self::json('POST', '/'));

        $this->assertEquals(405, $response->getStatusCode());
    }

    /**
     * @covers HomeAction::handle
     */
    public function testSuccess(): void
    {
        $response = $this->app()->handle(self::json('GET', '/'));

        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        $this->assertEquals('[]', (string)$response->getBody());
        $this->assertEquals(200, $response->getStatusCode());
    }
}
