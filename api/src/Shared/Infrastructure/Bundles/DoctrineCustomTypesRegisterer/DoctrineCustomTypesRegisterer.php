<?php
declare(strict_types=1);

namespace App\Shared\Infrastructure\Bundles\DoctrineCustomTypesRegisterer;

use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\CacheItem;
use Symfony\Contracts\Cache\CacheInterface;

//TODO: scheduled for complete refactor
class DoctrineCustomTypesRegisterer
{
    private static int $count = 0;

    private static bool $hasRegistered = false;


    public static function addTypes(array $customTypes): void
    {
        foreach ($customTypes as $customType) {
            self::addType($customType);
        }
    }

    public static function addType(string $customType): void
    {
        self::$count++;
        $key = self::getTypeCacheKey(self::$count);

        self::cacheDelete($key);
        self::cacheSet($key, $customType);
    }

    public static function register(EntityManagerInterface $entityManager): void
    {
        if (self::shouldRegister()) {
            foreach (self::getCachedTypes() as $typeClass) {
                $typeObject = new $typeClass();
                self::registerType($entityManager, $typeObject);
            }
        }
    }

    private static function shouldRegister(): bool
    {
        if (true === self::$hasRegistered) {
            return false;
        } else {
            return self::$hasRegistered = true;
        }
    }

    private static function getCachedTypes(): array
    {
        $types = [];
        for ($typeId = 1; ; $typeId++) {
            $type = self::cacheGet(self::getTypeCacheKey($typeId));

            if (false === $type instanceof CacheItem) {
                $types[] = $type;
            } else {
                break;
            }
        }

        return $types;
    }

    private static function registerType(
        EntityManagerInterface $entityManager,
        DoctrineCustomTypeInterface $type
    ): void {
        Type::addType($type->getName(), $type::class);
        $entityManager->getConnection()->getDatabasePlatform()->registerDoctrineTypeMapping($type::class, $type->getName());
    }

    private static function cacheGet(string $key): CacheItem|string
    {
        return self::getCacheAdapter()->get($key, fn($item) => $item);
    }

    private static function cacheSet(string $key, string $value): void
    {
        self::getCacheAdapter()->get($key, fn() => $value);
    }

    private static function cacheDelete(string $key): void
    {
        self::getCacheAdapter()->delete($key);
    }

    private static function getCacheAdapter(): CacheInterface
    {
        static $cache;
        if (false === isset($cache)) {
            $cache = new FilesystemAdapter();
        }

        return $cache;
    }

    private static function getTypeCacheKey(int $typeId): string
    {
        return sprintf('types_%d', $typeId);
    }
}
