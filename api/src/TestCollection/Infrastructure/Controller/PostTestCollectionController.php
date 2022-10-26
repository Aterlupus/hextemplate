<?php
declare(strict_types=1);

namespace App\TestCollection\Infrastructure\Controller;

use App\Shared\Infrastructure\Controller\AbstractPostController;
use App\TestCollection\Application\Create\CreateTestCollectionCommand;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints as Assert;

class PostTestCollectionController extends AbstractPostController
{
    protected function getCreationCommandClass(): string
    {
        return CreateTestCollectionCommand::class;
    }

    protected static function getValidationConstraints(): array
    {
        return [
            'name' => [new Assert\Required, new Assert\Length(['min' => 1, 'max' => 255])],
        ];
    }

    #[Route('/api/test-collection', methods: ['POST'])]
    public function getTestCollection(Request $request): Response
    {
        return $this->handleResourceCreation($request);
    }
}
