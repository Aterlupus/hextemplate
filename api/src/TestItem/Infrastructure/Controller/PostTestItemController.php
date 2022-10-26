<?php
declare(strict_types=1);

namespace App\TestItem\Infrastructure\Controller;

use App\Shared\Infrastructure\Controller\AbstractPostController;
use App\TestItem\Application\Create\CreateTestItemCommand;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints as Assert;

class PostTestItemController extends AbstractPostController
{
    protected function getCreationCommandClass(): string
    {
        return CreateTestItemCommand::class;
    }

    protected static function getValidationConstraints(): array
    {
        return [
            'description' => [new Assert\Required, new Assert\Length(['min' => 3, 'max' => 255])],
            'amount' => [],
            'testCollectionId' => [],
        ];
    }

    #[Route('/api/test-item', methods: ['POST'])]
    public function getTestCollection(Request $request): Response
    {
        return $this->handleResourceCreation($request);
    }
}
