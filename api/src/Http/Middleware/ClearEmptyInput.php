<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ClearEmptyInput implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $request = $request->withParsedBody(self::filterString($request->getParsedBody()));

        return $handler->handle($request);
    }

    /**
     * @param null|array|object $items
     *
     * @return null|array|object
     */
    private static function filterString($items)
    {
        if (!is_array($items)) {
            return $items;
        }

        $result = [];
        foreach ($items as $key => $value) {
            if (is_string($value)) {
                $result[$key] = trim($value);
            } else {
                $result[$key] = self::filterString($value);
            }
        }

        return $result;
    }
}
