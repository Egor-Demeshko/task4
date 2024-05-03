<?php

namespace App\Controller;

use App\Exception\ClearError;
use App\Service\ApiUser;
use App\Service\RemoveRolesOnBlock;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class APIBLOCKController extends AbstractController
{
    #[Route('/api/v1/users/block', name: '/api/v1/users/block', methods: ["POST"])]
    public function index(
        ApiUser $apiUser,
        Request $request,
        RemoveRolesOnBlock $removeRolesOnBlock
    ): Response {
        $status = false;
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $clearIds = [];

        $data = $request->getContent();

        try {
            $clearIds = $apiUser->clearIncoming($data);
            $status = $apiUser->setStatusBlocked($clearIds);
        } catch (ClearError $e) {
            $status = false;
        }

        if ($status === true) {
            $isRedirect = $removeRolesOnBlock->resetRoleOnMatch($clearIds, $this->getUser());
            if ($isRedirect) {
                return $this->json(
                    [
                        'status' => $status,
                        'redirect' => true
                    ]
                );
            }
        }

        return $this->json([
            'status' => $status
        ]);
    }
}
