<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\LoginType;
use App\Form\RegistrationType;
use App\Entity\User;
use App\Entity\UserDetails;

class MainController extends AbstractController
{
    #[Route('/', name: 'main')]
    public function index(Request $request): Response
    {
        $session = $request->getSession();

        return $this->checkSession($session->has("name"));
    }

    private function checkSession(bool $name): Response
    {
        if ($name) {
            return $this->redirectToRoute('users');
        } else {
            return $this->renderMain();
        }
    }

    private function renderMain()
    {
        $user = new User();
        $registration = new UserDetails();

        $login = $this->createForm(LoginType::class, $user);
        $register = $this->createForm(RegistrationType::class, $registration);

        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
            'login' => $login,
            'register' => $register
        ]);
    }
}
