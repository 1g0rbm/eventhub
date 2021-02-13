<?php

declare(strict_types=1);

use App\Console\FixturesLoadCommand;
use App\Console\MailerCheckCommand;
use Doctrine\Migrations\Tools\Console\Command;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Console\Command\SchemaTool;
use Psr\Container\ContainerInterface;

return [
    FixturesLoadCommand::class => static function (ContainerInterface $container) {
        /**
         * @psalm-suppress MixedArrayAccess
         * @psalm-var      array{fixtures_paths:array<array-key, string>} $config
         */
        $config = $container->get('config')['console'];

        return new FixturesLoadCommand(
            $container->get(EntityManagerInterface::class),
            $config['fixtures_paths']
        );
    },
    'config' => [
        'console' => [
            'commands' => [
                MailerCheckCommand::class,
                FixturesLoadCommand::class,
                SchemaTool\DropCommand::class,
                Command\DiffCommand::class,
                Command\GenerateCommand::class,
            ],
            'fixtures_paths' => [__DIR__ . '/../../src/Auth/Fixtures'],
        ],
    ],
];
