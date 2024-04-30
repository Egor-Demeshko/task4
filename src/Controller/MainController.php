<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\RegistrationType;
use App\Controller\Utils\FormUtils;
use App\Entity\Registration;
use App\Entity\User;
use App\Entity\UserDetails;
use App\Form\UserDetailsType;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;

class MainController extends AbstractController
{
    private array $formsOptions = [
        /* [
            'type' => FormUtils::LOGIN,
            'className' => LoginType::class,
            'orm' => User::class,
            'redirectTo' => 'users'
        ],*/
        [
            'type' => FormUtils::REGISTRATION,
            'className' => UserDetailsType::class,
            'orm' => UserDetails::class,
            'redirectTo' => 'success'
        ]
    ];
    private Request $request;
    private EntityManagerInterface $entityManager;
    private FormUtils $formsUtil;
    private string $sumbmittedRoute = '';


    #[Route('/', name: 'main')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->request = $request;
        $this->entityManager = $entityManager;
        $session = $request->getSession();
        return $this->redirectOnSession($session->has("name"));
    }

    private function redirectOnSession(bool $name): Response
    {
        if ($name) {
            return $this->redirectToRoute('users');
        } else {
            return $this->processThisController();
        }
    }

    private function processThisController(): Response
    {
        $this->processForms();

        if ($this->request->isMethod('POST')) {
            $submittedRoute = $this->defineSubmittedFormName();
            $this->formsUtil->setSubmittedRoute($submittedRoute);
        }
        return $this->chooseRender();
    }

    private function processForms(): void
    {
        $this->formsUtil = new FormUtils($this->formsOptions, $this);
    }

    private function chooseRender(): Response
    {
        if ($this->request->isMethod('POST')) {
            return $this->processPOST();
        }

        return $this->renderMain();
    }

    private function defineSubmittedFormName(): string
    {
        if (isset($this->request)) {
            $formArray = $this->request->request->all();
            foreach ($formArray as $formName => $_) {
                $this->sumbmittedRoute = $formName;
                return $formName;
            }
        }
    }

    private function renderMain(): Response
    {
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
            // FormUtils::LOGIN => $this->formsUtil->getForms()[FormUtils::LOGIN]['form'],
            FormUtils::REGISTRATION => $this->formsUtil->getForms()[FormUtils::REGISTRATION]['form']
        ]);
    }

    private function processPOST(): Response
    {
        if ($this->sumbmittedRoute === FormUtils::REGISTRATION) {
            $this->formsUtil->setRegistrationDataFromRequest();

            if ($this->formsUtil->isSubmittedAndValid()) {
                $orm = $this->formsUtil->getOrm(FormUtils::REGISTRATION);

                $this->entityManager->persist($orm);
                $this->entityManager->flush();
                return $this->formsUtil->redirect($this);
            }
        }

        return $this->renderMain();
    }

    public function getNewForm(string $className, mixed $orm): FormInterface
    {
        return $this->createForm($className, $orm);
    }

    public function goto(string $route): Response
    {
        return $this->redirectToRoute($route);
    }

    public function getRequest()
    {
        return $this->request;
    }
}
