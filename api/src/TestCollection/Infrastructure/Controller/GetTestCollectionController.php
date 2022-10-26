<?php
declare(strict_types=1);

namespace App\TestCollection\Infrastructure\Controller;

use App\Shared\Infrastructure\Controller\AbstractController;
use App\Shared\Infrastructure\Controller\Response\ResourceNotFoundResponse;
use App\Shared\Infrastructure\Controller\Response\SuccessResponse;
use App\TestCollection\Application\Get\TestCollectionQuery;
use App\TestCollection\Domain\TestCollection;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GetTestCollectionController extends AbstractController
{
    #[Route('/api/test-collection/{id}', methods: ['GET'])]
    public function getTestCollection(string $id): Response
    {
        $testCollection = $this->dispatchQuery(new TestCollectionQuery($id));

        if (null !== $testCollection) {
            return new SuccessResponse($testCollection);
        } else {
            return new ResourceNotFoundResponse(TestCollection::class, $id);
        }
    }
}
