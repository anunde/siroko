<?php

namespace App\Tests\Functional;

use App\Cart\Infrastructure\Doctrine\Fixtures\CartFixture;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Bundle\FrameworkBundle\Console\Application;

class TestBase extends WebTestCase
{
    protected EntityManagerInterface $entityManager;
    protected static ?KernelBrowser $client = null;

    protected function setUp(): void
    {
        parent::setUp();

        if (null === self::$client) {
            self::$client = static::createClient();
            self::$client->setServerParameters([
                "CONTENT_TYPE" => "application/json",
                "ACCEPT" => "application/json"
            ]);
        }

        $this->executeMigrations();
        $this->entityManager = static::getContainer()->get('doctrine')->getManager();

        $this->purgeAndLoadFixtures();
    }

    protected static function getKernelClass(): string
    {
        return \App\Kernel::class;
    }

    private function executeMigrations(): void
    {
        $application = new Application(self::bootKernel());
        $application->setAutoExit(false);

        $application->run(new ArrayInput([
            'command' => 'doctrine:database:create',
            '--env' => 'test',
            '--if-not-exists' => true,
        ]));

        $application->run(new ArrayInput([
            'command' => 'doctrine:migrations:migrate',
            '--env' => 'test',
            '--no-interaction' => true,
        ]));
    }

    private function purgeAndLoadFixtures(): void
    {
        $purger = new ORMPurger($this->entityManager);
        $purger->purge();

        $loader = new Loader();
        $loader->addFixture(new CartFixture());

        $executor = new ORMExecutor($this->entityManager, $purger);
        $executor->execute($loader->getFixtures());
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
        unset($this->entityManager);
    }

    protected function getResponseData(Response $response)
    {
        return \json_decode($response->getContent(), true);
    }
}
