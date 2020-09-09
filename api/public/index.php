<?php

declare(strict_types = 1);

use DI\Container;
use Slim\App;

http_response_code(500);

require __DIR__.'/../vendor/autoload.php';

/** @var Container $container */
$container = require __DIR__.'/../config/container.php';

/** @var App $app */
$app = (require __DIR__.'/../config/app.php')($container);
$app->run();
