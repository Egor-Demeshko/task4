<?php

namespace App\Controller;

use App\Service\ApiUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class API_LISTController extends AbstractController
{
    #[Route('/api/v1/users/list', name: '/api/v1/users/list')]
    public function index(ApiUser $apiUser): JsonResponse
    {
        $status = false;
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $data = $apiUser->getUsers() ?? null;

        if ($data) {
            $status = true;
        }

        //need to get all users data
        //create service, it will trigger fetch all users data, and prepare it


        return $this->json([
            'status' => $status,
            'data' => $data
        ]);
    }
}
