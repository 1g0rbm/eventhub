<?php

declare(strict_types=1);

namespace Test\Functional;

use DMS\PHPUnitExtensions\ArraySubset\ArraySubsetAsserts;
use Throwable;

class NotFoundTest extends WebTestCase
{
    use ArraySubsetAsserts;

    /**
     * @throws Throwable
     */
    public function testNotExistedRouteReturnStatus404(): void
    {
        $response = $this->app()->handle(self::json('GET', '/not-found-page'));
        self::assertEquals(404, $response->getStatusCode());
        self::assertJson($body = (string)$response->getBody());

        $data = Json::decode($body);

        self::assertArraySubset(
            [
                'message' => '404 Not Found',
            ],
            $data
        );
    }
}
