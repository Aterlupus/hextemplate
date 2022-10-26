<?php
declare(strict_types=1);

namespace App\TestItem\Infrastructure\Controller;

use App\Shared\Infrastructure\Controller\AbstractController;
use App\Shared\Infrastructure\Controller\Response\ResourceNotFoundResponse;
use App\Shared\Infrastructure\Controller\Response\SuccessResponse;
use App\TestItem\Application\Get\TestItemQuery;
use App\TestItem\Domain\TestItem;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GetTestItemController extends AbstractController
{
    #[Route('/api/test-item/{id}', methods: ['GET'])]
    public function getTestItem(string $id): Response
    {
        $testItem = $this->dispatchQuery(new TestItemQuery($id));

        if (null !== $testItem) {
            return new SuccessResponse($testItem);
        } else {
            return new ResourceNotFoundResponse(TestItem::class, $id);
        }
    }
}
