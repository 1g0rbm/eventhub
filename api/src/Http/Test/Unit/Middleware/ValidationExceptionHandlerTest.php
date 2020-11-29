<?php

declare(strict_types=1);

namespace App\Http\Test\Unit\Middleware;

use App\Http\Middleware\ValidationExceptionHandler;
use App\Http\Validator\ValidationException;
use PHPUnit\Framework\TestCase;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Psr7\Factory\ServerRequestFactory;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;

use function json_decode;

class ValidationExceptionHandlerTest extends TestCase
{
    public function testNormal(): void
    {
        $middleware = new ValidationExceptionHandler();

        $source = (new ResponseFactory())->createResponse();

        $handler = $this->createStub(RequestHandlerInterface::class);
        $handler->method('handle')->willReturn($source);

        $request  = (new ServerRequestFactory())->createServerRequest('POST', 'http://test');
        $response = $middleware->process($request, $handler);

        self::assertEquals($source, $response);
    }

    public function testException(): void
    {
        $middleware = new ValidationExceptionHandler();

        $violations = new ConstraintViolationList(
            [
                new ConstraintViolation(
                    'Incorrect Email',
                    null,
                    [],
                    null,
                    'email',
                    'not-email'
                ),
                new ConstraintViolation(
                    'Empty Password',
                    null,
                    [],
                    null,
                    'password',
                    ''
                ),
            ]
        );

        $handler = $this->createStub(RequestHandlerInterface::class);
        $handler->method('handle')->willThrowException(new ValidationException($violations));

        $request  = (new ServerRequestFactory())->createServerRequest('POST', 'http://test');
        $response = $middleware->process($request, $handler);

        self::assertEquals(422, $response->getStatusCode());
        self::assertJson($body = (string)$response->getBody());

        /** @var array $data */
        $data = json_decode($body, true, JSON_THROW_ON_ERROR);

        self::assertEquals(
            [
                'errors' => [
                    'email' => 'Incorrect Email',
                    'password' => 'Empty Password',
                ],
            ],
            $data
        );
    }
}
