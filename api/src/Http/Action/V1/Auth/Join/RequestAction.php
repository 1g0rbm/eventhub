<?php

declare(strict_types=1);

namespace App\Http\Action\V1\Auth\Join;

use App\Auth\Command\JoinByEmail\Request\Command;
use App\Auth\Command\JoinByEmail\Request\Handler;
use App\Http\JsonResponse;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use DomainException;
use JsonException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use stdClass;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use function trim;

class RequestAction implements RequestHandlerInterface
{
    private Handler $handler;

    public function __construct(Handler $handler)
    {
        $this->handler = $handler;
    }

    /**
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     * @throws NoResultException
     * @throws NonUniqueResultException
     * @throws JsonException
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        /** @psalm-var array{email:?string, password:?string} $data */
        $data = $request->getParsedBody();

        $command           = new Command();
        $command->email    = trim($data['email'] ?? '');
        $command->password = trim($data['password'] ?? '');

        $this->handler->handle($command);

        return new JsonResponse(new stdClass(), 201);
    }
}
