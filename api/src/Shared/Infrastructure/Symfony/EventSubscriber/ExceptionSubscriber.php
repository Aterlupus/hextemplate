<?php
declare(strict_types=1);

namespace App\Shared\Infrastructure\Symfony\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ExceptionSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => [
                'processException'
            ],
        ];
    }

    public function processException(ExceptionEvent $event): void
    {
        $response = ExceptionToHttpResponseMapper::getResponse($event->getThrowable());

        if (null !== $response) {
            $event->setResponse($response);
        }
    }
}
