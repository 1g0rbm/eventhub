<?php

declare(strict_types=1);

namespace App;

use JsonException;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Headers;
use Slim\Psr7\Response;

use function json_encode;

class JsonResponse extends Response
{
    /**
     * @param mixed $data
     * @param int   $status
     *
     * @throws JsonException
     */
    public function __construct($data, int $status = 200)
    {
        parent::__construct(
            $status,
            new Headers(['Content-Type' => 'application/json']),
            (new StreamFactory())->createStream(json_encode($data, JSON_THROW_ON_ERROR))
        );
    }
}
