<?php

declare(strict_types=1);

namespace App\Http\Action;

use App\FeatureToggle\FeatureFlag;
use App\Http\JsonResponse;
use JsonException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;

class HomeAction implements RequestHandlerInterface
{
    private FeatureFlag $flag;

    public function __construct(FeatureFlag $flag)
    {
        $this->flag = $flag;
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws JsonException
     */
    public function handle(Request $request): Response
    {
        if ($this->flag->isEnabled('NEW_HOME')) {
            return new JsonResponse(['name' => 'API']);
        }

        return new JsonResponse([]);
    }
}
