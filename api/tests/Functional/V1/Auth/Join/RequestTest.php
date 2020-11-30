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
        $this->mailer()->clear();

        $response = $this->app()->handle(
            self::json(
                'POST',
                '/v1/auth/join',
                [
                    'email' => 'new-user@app.test',
                    'password' => 'new-password',
                ]
            )
        );

        self::assertEquals(201, $response->getStatusCode());
        self::assertEquals('{}', (string)$response->getBody());

        self::assertTrue($this->mailer()->hasEmailSentTo('new-user@app.test'));
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

        self::assertEquals(422, $response->getStatusCode());
        self::assertJson($body = (string)$response->getBody());

        self::assertEquals(
            [
                'errors' => [
                    'email' => 'This value should not be blank.',
                    'password' => 'This value should not be blank.',
                ],
            ],
            Json::decode($body)
        );
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

        self::assertEquals(422, $response->getStatusCode());
        self::assertJson($body = (string)$response->getBody());

        self::assertEquals(
            [
                'errors' => [
                    'email' => 'This value is not a valid email address.',
                    'password' => 'This value should not be blank.',
                ],
            ],
            Json::decode($body)
        );
    }

    /**
     * @throws Throwable
     */
    public function testNotValidLang(): void
    {
        $this->markTestIncomplete();

        $response = $this->app()->handle(
            self::json(
                'POST',
                '/v1/auth/join',
                [
                    'email' => 'not-valid',
                    'password' => '',
                ]
            )
        )->withHeader('Accept-Language', 'es;q=0.9, ru;q=0.8, *;q=0.5');

        self::assertEquals(422, $response->getStatusCode());
        self::assertJson($body = (string)$response->getBody());

        self::assertEquals(
            [
                'errors' => [
                    'email' => 'Значение адреса электронной почты недопустимо.',
                    'password' => 'Значение не должно быть пустым.',
                ],
            ],
            Json::decode($body)
        );
    }
}
