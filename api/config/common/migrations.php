<?php

declare(strict_types=1);

use Doctrine\Migrations\Configuration\Configuration;
use Doctrine\Migrations\Configuration\EntityManager\ExistingEntityManager;
use Doctrine\Migrations\Configuration\Migration\ExistingConfiguration;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\Migrations\Metadata\Storage\TableMetadataStorageConfiguration;
use Doctrine\Migrations\Tools\Console\Command\DiffCommand;
use Doctrine\Migrations\Tools\Console\Command\ExecuteCommand;
use Doctrine\Migrations\Tools\Console\Command\GenerateCommand;
use Doctrine\Migrations\Tools\Console\Command\LatestCommand;
use Doctrine\Migrations\Tools\Console\Command\ListCommand;
use Doctrine\Migrations\Tools\Console\Command\MigrateCommand;
use Doctrine\Migrations\Tools\Console\Command\StatusCommand;
use Doctrine\Migrations\Tools\Console\Command\UpToDateCommand;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;

$factoryExtractor = function (ContainerInterface $container): DependencyFactory {
    /** @var DependencyFactory $factory */
    $factory = $container->get(DependencyFactory::class);

    return $factory;
};

return [
    DependencyFactory::class => static function (ContainerInterface $container) {
        /** @var EntityManagerInterface $em */
        $em = $container->get(EntityManagerInterface::class);

        $configuration = new Configuration();
        $configuration->addMigrationsDirectory(
            'App\Data\Migration',
            __DIR__ . '/../../src/Data/Migrations'
        );
        $configuration->setAllOrNothing(true);
        $configuration->setCheckDatabasePlatform(false);

        $storageConfiguration = new TableMetadataStorageConfiguration();
        $storageConfiguration->setTableName('migrations');

        $configuration->setMetadataStorageConfiguration($storageConfiguration);

        return DependencyFactory::fromEntityManager(
            new ExistingConfiguration($configuration),
            new ExistingEntityManager($em)
        );
    },
    ExecuteCommand::class => static fn(ContainerInterface $container) => new ExecuteCommand(
        $factoryExtractor($container)
    ),
    MigrateCommand::class => static fn(ContainerInterface $container) => new MigrateCommand(
        $factoryExtractor($container)
    ),
    LatestCommand::class => static fn(ContainerInterface $container) => new LatestCommand(
        $factoryExtractor($container)
    ),
    ListCommand::class => static fn(ContainerInterface $container) => new ListCommand(
        $factoryExtractor($container)
    ),
    StatusCommand::class => static fn(ContainerInterface $container) => new StatusCommand(
        $factoryExtractor($container)
    ),
    UpToDateCommand::class => static fn(ContainerInterface $container) => new UpToDateCommand(
        $factoryExtractor($container)
    ),
    DiffCommand::class => static fn(ContainerInterface $container) => new DiffCommand(
        $factoryExtractor($container)
    ),
    GenerateCommand::class => static fn(ContainerInterface $container) => new GenerateCommand(
        $factoryExtractor($container)
    ),
];
