<?php

declare(strict_types=1);

namespace App\Http\Action\V1\Auth\Join;

use App\Auth\Command\JoinByEmail\Confirm\Command;
use App\Auth\Command\JoinByEmail\Confirm\Handler;
use App\Http\JsonResponse;
use JsonException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use stdClass;

use function trim;

class ConfirmAction implements RequestHandlerInterface
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
     * @throws JsonException
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        /** @psalm-var array{token:?string} $data */
        $data = $request->getParsedBody();

        $command           = new Command();
        $command->token    = trim($data['token'] ?? '');

        $this->handler->handle($command);

        return new JsonResponse(new stdClass(), 200);
    }
}
