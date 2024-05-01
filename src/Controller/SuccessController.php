<?php

namespace App\Controller;

use LDAP\Result;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SuccessController extends AbstractController
{
    #[Route('/success', name: 'success')]
    public function index(Request $request): Response
    {
        $email = $request?->get('email') ?? '';
        return $this->render('success/index.html.twig', [
            'controller_name' => 'SuccessController',
            'email' => $email
        ]);
    }
}
