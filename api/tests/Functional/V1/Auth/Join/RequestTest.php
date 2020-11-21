<?php

declare(strict_types=1);

namespace Test\Functional\V1\Auth\Join;

use Test\Functional\Json;
use Test\Functional\WebTestCase;
use Throwable;

class RequestTest extends WebTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->loadFixtures([RequestFixture::class]);
    }

    /**
     * @throws Throwable
     */
    public function testMethod(): void
    {
        $response = $this->app()->handle(self::json('GET', '/v1/auth/join'));

        self::assertEquals(405, $response->getStatusCode());
    }

    /**
     * @throws Throwable
     */
    public function testSuccess(): void
    {
        $response = $this->app()->handle(
            self::json(
                'POST',
                '/v1/auth/join',
                [
                    'email' => 'user@app.test',
                    'password' => 'new-password',
                ]
            )
        );

        self::assertEquals(201, $response->getStatusCode());
        self::assertEquals('{}', (string)$response->getBody());
    }

    /**
     * @throws Throwable
     */
    public function testExisting(): void
    {
        $response = $this->app()->handle(
            self::json(
                'POST',
                '/v1/auth/join',
                [
                    'email' => RequestFixture::EXISTING_EMAIL,
                    'password' => 'new-password',
                ]
            )
        );

        self::assertEquals(409, $response->getStatusCode());
        self::assertJson($body = (string)$response->getBody());

        self::assertEquals(
            [
                'message' => 'user_already_exist',
            ],
            Json::decode($body)
        );
    }

    /**
     * @throws Throwable
     */
    public function testEmpty(): void
    {
        $response = $this->app()->handle(self::json('POST', '/v1/auth/join', []));

        self::assertEquals(500, $response->getStatusCode());
    }

    /**
     * @throws Throwable
     */
    public function testNotValid(): void
    {
        $response = $this->app()->handle(
            self::json(
                'POST',
                '/v1/auth/join',
                [
                    'email' => 'not-valid',
                    'password' => '',
                ]
            )
        );

        self::assertEquals(500, $response->getStatusCode());
    }
}
