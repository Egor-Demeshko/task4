<?php

namespace App\Controller;

use App\Exception\ClearError;
use App\Service\ApiUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class APIUNBLOCKController extends AbstractController
{
    #[Route('/api/v1/users/unblock', name: '/api/v1/users/unblock', methods: [Request::METHOD_POST])]
    public function index(ApiUser $apiUser, Request $request): Response
    {
        $status = false;
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $clearIds = [];

        $data = $request->getContent();

        try {
            $clearIds = $apiUser->clearIncoming($data);
            $status = $apiUser->setStatusUnBlocked($clearIds);
        } catch (ClearError $e) {
            $status = false;
        }

        return $this->json([
            'status' => $status
        ]);
    }
}
