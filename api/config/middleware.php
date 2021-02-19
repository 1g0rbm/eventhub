<?php

declare(strict_types=1);

use App\FeatureToggle\FeaturesMiddleware;
use App\Http\Middleware\ClearEmptyInput;
use App\Http\Middleware\DomainExceptionHandler;
use App\Http\Middleware\TranslatorLocale;
use App\Http\Middleware\ValidationExceptionHandler;
use Middlewares\ContentLanguage;
use Slim\App;
use Slim\Middleware\ErrorMiddleware;

return static function (App $app): void {
    $app->add(DomainExceptionHandler::class);
    $app->add(ValidationExceptionHandler::class);
    $app->add(ClearEmptyInput::class);
    $app->add(TranslatorLocale::class);
    $app->add(FeaturesMiddleware::class);
    $app->add(ContentLanguage::class);
    $app->addBodyParsingMiddleware();
    $app->add(ErrorMiddleware::class);
};
