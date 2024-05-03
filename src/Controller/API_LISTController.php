<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class API_LISTController extends AbstractController
{
    #[Route('/api/v1/users/list', name: '/api/v1/users/list')]
    public function index(): JsonResponse
    {
        $status = false;
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        //need to get all users data
        //create service, it will trigger fetch all users data, and prepare it


        return $this->json([
            'status' => $status,
        ]);
    }
}
