<?php
declare(strict_types=1);

namespace Test\Functional\Shared;

use App\Core\Util\Set;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Test\Helper\EntityGenerator;
use Test\Helper\EntityManagerTrait;

abstract class AbstractFunctionalTest extends WebTestCase
{
    use EntityManagerTrait,
        FunctionalExecutionTrait;

    private KernelBrowser $client;

    protected EntityManagerInterface $entityManager;

    protected EntityGenerator $eg;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
        $this->entityManager = $this->getService('doctrine.orm.entity_manager');
        $this->eg = new EntityGenerator($this->entityManager);
        $this->truncateEntities();
    }

    protected function getService(string $service): object
    {
        return static::$kernel->getContainer()->get($service);
    }

    protected function getParameter(string $parameter): array|string|float|int|bool|null
    {
        return static::$kernel->getContainer()->getParameter($parameter);
    }

    private function truncateEntities(): void
    {
        $purger = new ORMPurger($this->getEntityManager());
        $purger->purge();
    }

    protected function assertNotResponseCode(int $code, ?string $message = ''): void
    {
        self::assertNotEquals($code, $this->getResponseCode(), $message);
    }

    protected function assertResponseMessageSubstring(string $substring): void
    {
        self::assertStringContainsString($substring, $this->getResponseJson());
    }

    protected static function getOnlyViolation(array $response): array
    {
        self::assertCount(1, $response['violations']);
        return Set::getOnly($response['violations']);
    }

    protected function getClientBrowser(): KernelBrowser
    {
        return $this->client;
    }

    protected function getEntityManager(): EntityManagerInterface
    {
        return $this->entityManager;
    }
}
