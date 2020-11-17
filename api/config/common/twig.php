<?php

declare(strict_types=1);

use Twig\Loader\FilesystemLoader;

return [
    'config' => [
        'twig' => [
            'debug' => (bool)getenv('APP_DEBUG'),
            'template_dirs' => [
                FilesystemLoader::MAIN_NAMESPACE => __DIR__ . '/../../templates',
            ],
            'cache_dir' => __DIR__ . '/../../var/cache/twig',
            'extension' => [],
        ],
    ],
];
