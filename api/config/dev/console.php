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
         * @psalm-var      array{fixtures_paths:string[] $config}
         */
        $config = $container->get('config')['console'];

        /** @var EntityManagerInterface $em */
        $em = $container->get(EntityManagerInterface::class);

        return new FixturesLoadCommand(
            $em,
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
