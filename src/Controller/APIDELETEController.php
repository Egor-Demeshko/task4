<?php

namespace App\Controller;

use App\Exception\ClearError;
use App\Service\ApiUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class APIDELETEController extends AbstractController
{
    #[Route('/api/v1/users/delete', name: 'delete', methods: [Request::METHOD_DELETE])]
    public function index(Request $request, ApiUser $apiUser): Response
    {
        $status = false;
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $clearIds = [];

        $data = $request->getContent();

        try {
            $clearIds = $apiUser->clearIncoming($data);
            $status = $apiUser->delete($clearIds);
        } catch (ClearError $e) {
            $status = false;
        }

        return $this->json([
            'status' => $status
        ]);
    }
}
