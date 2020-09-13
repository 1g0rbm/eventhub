<?php

declare(strict_types=1);

namespace Test\Functional;

use App\Http\Action\HomeAction;

class NotFoundTest extends WebTestCase
{
    /**
     * @covers HomeAction::handle
     */
    public function testNotExistedRouteReturnStatus404(): void
    {
        $response = $this->app()->handle(self::json('GET', '/not-found-page'));

        $this->assertEquals(404, $response->getStatusCode());
    }
}
