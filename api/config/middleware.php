<?php

declare(strict_types=1);

use Slim\App;
use DI\Container;

return static function (App $app, Container $container) {
    /** @psalm-var array{debug:bool, env:string} */
    $config = $container->get('config');
    $app->addErrorMiddleware(
        $config['debug'],
        $config['env'] !== 'test',
        true
    );
};
