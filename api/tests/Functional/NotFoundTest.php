<?php

declare(strict_types=1);

namespace Test\Functional;

class NotFoundTest extends WebTestCase
{
    public function testNotExistedRouteReturnStatus404(): void
    {
        $response = $this->app()->handle(self::json('GET', '/not-found-page'));

        $this->assertEquals(404, $response->getStatusCode());
    }
}
