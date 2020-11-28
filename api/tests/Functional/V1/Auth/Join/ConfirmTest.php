<?php

declare(strict_types=1);

namespace Test\Functional\V1\Auth\Join;

use Test\Functional\Json;
use Test\Functional\WebTestCase;
use Throwable;

class ConfirmTest extends WebTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->loadFixtures(
            [
                ConfirmFixture::class,
            ]
        );
    }

    /**
     * @throws Throwable
     */
    public function testMethod(): void
    {
        $response = $this->app()->handle(self::json('GET', '/v1/auth/join/confirm'));

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
                '/v1/auth/join/confirm',
                [
                    'token' => ConfirmFixture::VALID,
                ]
            )
        );

        self::assertEquals(200, $response->getStatusCode());
        self::assertEquals('{}', (string)$response->getBody());
    }

    /**
     * @throws Throwable
     */
    public function testExpired(): void
    {
        $response = $this->app()->handle(
            self::json(
                'POST',
                '/v1/auth/join/confirm',
                [
                    'token' => ConfirmFixture::EXPIRED,
                ]
            )
        );

        self::assertEquals(409, $response->getStatusCode());
        self::assertJson($body = (string)$response->getBody());

        self::assertEquals(['message' => 'expired_confirmation_token'], Json::decode($body));
    }

    /**
     * @throws Throwable
     */
    public function testEmpty(): void
    {
        $response = $this->app()->handle(
            self::json(
                'POST',
                '/v1/auth/join/confirm',
                []
            )
        );

        self::assertEquals(422, $response->getStatusCode());
        self::assertJson($body = (string)$response->getBody());

        self::assertEquals(['errors' => ['token' => 'This value should not be blank.']], Json::decode($body));
    }

    /**
     * @throws Throwable
     */
    public function testIncorrect(): void
    {
        $response = $this->app()->handle(
            self::json(
                'POST',
                '/v1/auth/join/confirm',
                [
                    'token' => 'invalid',
                ]
            )
        );

        self::assertEquals(409, $response->getStatusCode());
        self::assertJson($body = (string)$response->getBody());

        self::assertEquals(['message' => 'incorrect_token'], Json::decode($body));
    }
}
