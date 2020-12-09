<?php

declare(strict_types=1);

namespace App\Http\Action\V1\Auth\Join;

use App\Auth\Command\JoinByEmail\Confirm\Command;
use App\Auth\Command\JoinByEmail\Confirm\Handler;
use App\Http\JsonResponse;
use App\Http\Validator\Validator;
use JsonException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use stdClass;

use function trim;

class ConfirmAction implements RequestHandlerInterface
{
    private Handler $handler;

    private Validator $validator;

    public function __construct(Handler $handler, Validator $validator)
    {
        $this->handler   = $handler;
        $this->validator = $validator;
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

        $command        = new Command();
        $command->token = trim($data['token'] ?? '');

        $this->validator->validate($command);

        $this->handler->handle($command);

        return new JsonResponse(new stdClass(), 200);
    }
}