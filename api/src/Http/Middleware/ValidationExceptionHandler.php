<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Http\JsonResponse;
use App\Http\Validator\ValidationException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use JsonException;

class ValidationExceptionHandler implements MiddlewareInterface
{
    /**
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     * @throws JsonException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (ValidationException $e) {
            return new JsonResponse(['errors' => self::errorsArray($e->getViolations())], 422);
        }
    }

    private static function errorsArray(ConstraintViolationListInterface $violations): array
    {
        $errors = [];
        /** @var ConstraintViolation $violation */
        foreach ($violations as $violation) {
            $errors[$violation->getPropertyPath()] = $violation->getMessage();
        }

        return $errors;
    }
}
