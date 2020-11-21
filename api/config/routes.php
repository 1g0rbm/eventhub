<?php

declare(strict_types=1);

use App\Http\Action\HomeAction;
use App\Http\Action\V1\Auth\Join\RequestAction;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return static function (App $app): void {
    $app->get('/', HomeAction::class);
    $app->group(
        '/v1',
        function (RouteCollectorProxy $group): void {
            $group->group(
                '/auth',
                function (RouteCollectorProxy $group) {
                    $group->post('/join', RequestAction::class);
                }
            );
        }
    );
};
