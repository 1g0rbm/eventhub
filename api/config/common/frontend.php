<?php

declare(strict_types=1);

use App\Frontend\FrontendUrlGenerator;
use Psr\Container\ContainerInterface;

return [
    FrontendUrlGenerator::class => function (ContainerInterface $container): FrontendUrlGenerator {
        /**
         * @psalm-suppress MixedArrayAccess
         * @psalm-var      array{url:string} $frontend
         */
        $frontend = $container->get('config')['frontend'];

        return new FrontendUrlGenerator($frontend['url']);
    },
    'config' => [
        'frontend' => [
            'url' => getenv('FRONTEND_URL'),
        ],
    ],
];
