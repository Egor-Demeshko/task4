<?php


namespace App\EventListener;

use Symfony\Component\Security\Http\Event\LoginFailureEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;

#[AsEventListener]
class UnssuccessfulLogin
{
    public function __invoke(LoginFailureEvent $event)
    {
        $exception = $event->getException();
        $message = $exception->getMessage();
        $event->setResponse(new JsonResponse(["status" => false, 'data' => ["password" => [['message' => $message]]]], 401));
    }
}
