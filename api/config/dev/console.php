<?php

declare(strict_types=1);

use Doctrine\Migrations\Tools\Console\Command;
use Doctrine\ORM\Tools\Console\Command\SchemaTool;

return [
    'config' => [
        'console' => [
            'commands' => [
                SchemaTool\DropCommand::class,
                Command\DiffCommand::class,
                Command\GenerateCommand::class,
            ],
        ],
    ],
];
