<?php

declare(strict_types=1);

namespace App\Console;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FixturesLoadCommand extends Command
{
    private EntityManagerInterface $em;

    /**
     * @var string[]
     */
    private array $paths;

    public function __construct(EntityManagerInterface $em, array $paths)
    {
        parent::__construct();

        $this->em    = $em;
        $this->paths = $paths;
    }

    protected function configure(): void
    {
        $this->setName('fixtures:load')
            ->setDescription('Load fixtures');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<comment>Loading...</comment>');

        $loader = new Loader();
        foreach ($this->paths as $path) {
            $loader->loadFromDirectory($path);
        }

        $executor = new ORMExecutor($this->em, new ORMPurger());
        $executor->setLogger(static fn(string $message) => $output->writeln($message));
        $executor->execute($loader->getFixtures());

        $output->writeln('<info>Done!</info>');

        return 0;
    }
}
