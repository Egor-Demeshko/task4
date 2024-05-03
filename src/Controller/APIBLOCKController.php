<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class APIBLOCKController extends AbstractController
{
    #[Route('/api/v1/users/block', name: '/api/v1/users/block', methods: ["POST"])]
    public function index(): Response
    {
        $status = false;
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');


        return $this->render('apiblock/index.html.twig', [
            'controller_name' => 'APIBLOCKController',
        ]);
    }
}
