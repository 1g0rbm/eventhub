<?php

declare(strict_types=1);

namespace Test\Functional;

use DI\Container;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;
use Slim\Psr7\Factory\ServerRequestFactory;
use Throwable;

class WebTestCase extends TestCase
{
    private ?App $app = null;

    private ?MailerClient $mailer = null;

    protected function tearDown(): void
    {
        $this->app = null;

        parent::tearDown();
    }

    /**
     * @param string $method
     * @param string $path
     * @param array  $body
     *
     * @return ServerRequestInterface
     * @throws Throwable
     */
    protected static function json(string $method, string $path, array $body = []): ServerRequestInterface
    {
        $request = self::request($method, $path)
            ->withHeader('Accept', 'application/json')
            ->withHeader('Content-Type', 'application/json');

        $request->getBody()->write(json_encode($body, JSON_THROW_ON_ERROR));

        return $request;
    }

    protected static function request(string $method, string $path): ServerRequestInterface
    {
        return (new ServerRequestFactory())->createServerRequest($method, $path);
    }

    protected function app(): App
    {
        if ($this->app === null) {
            /** @var App */
            $this->app = (require __DIR__ . '/../../config/app.php')($this->container());
        }

        return $this->app;
    }

    protected function mailer(): MailerClient
    {
        if ($this->mailer === null) {
            $this->mailer = new MailerClient();
        }

        return $this->mailer;
    }

    /**
     * @param array<string|int, string> $fixtures
     */
    protected function loadFixtures(array $fixtures): void
    {
        /** @var ContainerInterface $container */
        $container = $this->app()->getContainer();

        $loader = new Loader();
        foreach ($fixtures as $name => $class) {
            /** @var AbstractFixture $fixtures */
            $fixtures = $container->get($class);
            $loader->addFixture($fixtures);
        }

        /** @var EntityManagerInterface $em */
        $em       = $container->get(EntityManagerInterface::class);
        $executor = new ORMExecutor($em, new ORMPurger());

        $executor->execute($loader->getFixtures());
    }

    private function container(): Container
    {
        /** @var Container $container */
        return require __DIR__ . '/../../config/container.php';
    }
}
