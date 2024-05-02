<?php


namespace App\EventListener;

use App\Repository\UserDetailsRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

#[AsEventListener]
final class LoginTime
{
    private UserDetailsRepository $repository;
    public function __construct(UserDetailsRepository $repository)
    {
        $this->repository = $repository;
    }
    public function __invoke(LoginSuccessEvent $event): void
    {
        $user = $event->getUser();
        $userDetails = $user?->getUserDetails() ?? null;

        if ($userDetails) {
            $date = new DateTime();
            $user_id = $user->getId();
            $this->repository->setLastLogginedAt($user_id, $date);
        }
    }
}
