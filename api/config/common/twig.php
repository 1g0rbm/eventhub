<?php

declare(strict_types=1);

use App\FeatureToggle\FeatureFlagTwigExtension;
use App\Frontend\FrontendUrlTwigExtension;
use Psr\Container\ContainerInterface;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Extension\ExtensionInterface;
use Twig\Loader\FilesystemLoader;

return [
    Environment::class => function (ContainerInterface $container): Environment {
        /**
         * @psalm-suppress MixedArrayAccess
         * @psalm-var      array {
         *  debug:bool,
         *  template_dirs:array<string,string>,
         *  cache_dir:string,
         *  extension:string[]
         * } $config
         */
        $config = $container->get('config')['twig'];

        $loader = new FilesystemLoader();

        /**
         * @var string $alias
         * @var string $dir
         */
        foreach ($config['template_dirs'] as $alias => $dir) {
            $loader->addPath($dir, $alias);
        }

        $env = new Environment(
            $loader,
            [
                'cache' => $config['debug'] ? false : $config['cache_dir'],
                'debug' => $config['debug'],
                'strict_variables' => $config['debug'],
                'auto_reload' => $config['debug'],
            ]
        );

        if ($config['debug']) {
            $env->addExtension(new DebugExtension());
        }

        /** @var string $class */
        foreach ($config['extension'] as $class) {
            /** @var ExtensionInterface $ext */
            $ext = $container->get($class);
            $env->addExtension($ext);
        }

        return $env;
    },
    'config' => [
        'twig' => [
            'debug' => (bool)getenv('APP_DEBUG'),
            'template_dirs' => [
                FilesystemLoader::MAIN_NAMESPACE => __DIR__ . '/../../templates',
            ],
            'cache_dir' => __DIR__ . '/../../var/cache/twig',
            'extension' => [
                FrontendUrlTwigExtension::class,
                FeatureFlagTwigExtension::class,
            ],
        ],
    ],
];
