<?php
declare(strict_types=1);

namespace Test\Functional;

use App\Core\Util\TypeInspector;
use App\Core\Uuid;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractGetFunctionalTest extends AbstractFunctionalTest
{
    abstract protected static function getUri(): string;

    protected function itFailsToGetNonExistent(string $entityClass): void
    {
        $id = Uuid::string();

        $response = $this->get(sprintf(static::getUri(), $id));
        $this->assertResponseCode(Response::HTTP_NOT_FOUND);

        self::assertEquals(sprintf('Resource %s of id "%s" not found', TypeInspector::getClassName($entityClass), $id), $response);
    }

    protected function testItFailsToGetNonExistentByInvalidId(): void
    {
        $id = 'xxx';

        $response = $this->get(sprintf(static::getUri(), $id));
        $this->assertResponseCode(Response::HTTP_NOT_FOUND);

        self::assertEquals('An error occurred', $response['title']);
        self::assertEquals('Invalid identifier value or configuration.', $response['detail']);
    }
}
