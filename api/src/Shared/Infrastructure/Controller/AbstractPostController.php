<?php
declare(strict_types=1);

namespace App\Shared\Infrastructure\Controller;

use App\Shared\Application\CQRS\CommandInterface;
use App\Shared\Application\CQRS\CreatableFromArrayInterface;
use App\Shared\Infrastructure\Controller\Response\BadRequestResponse;
use App\Shared\Infrastructure\Controller\Response\ResourceCreatedResponse;
use App\Shared\Infrastructure\Controller\Validation\RequestValidator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractPostController extends AbstractController
{
    abstract protected function getCreationCommandClass(): CreatableFromArrayInterface|string;

    abstract protected static function getValidationConstraints(): array;

    public function handleResourceCreation(Request $request): Response
    {
        $content = self::getRequestContent($request);
        $errors = RequestValidator::validate($content, static::getValidationConstraints());

        if (empty($errors)) {
            $command = $this->getCommand($content);
            $this->dispatchCommand($command);
            return new ResourceCreatedResponse($command->getId()); //Assumption that $command has "getId" method
        } else {
            return new BadRequestResponse(['errors' => $errors]);
        }
    }

    private function getCommand(array $content): CommandInterface
    {
        $commandClass = static::getCreationCommandClass();
        return $commandClass::createFromArray($content);
    }
}
