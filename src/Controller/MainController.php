<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Controller\Utils\FormUtils;
use App\Entity\User;
use App\Entity\UserDetails;
use App\Form\UserDetailsType;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Service\PasswordUtil;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\FormInterface;

class MainController extends AbstractController
{
    private array $formsOptions = [
        [
            'type' => FormUtils::LOGIN,
            'className' => UserType::class,
            'orm' => User::class,
            'redirectTo' => 'users',
        ],
        [
            'type' => FormUtils::REGISTRATION,
            'className' => UserDetailsType::class,
            'orm' => UserDetails::class,
            'redirectTo' => 'success',
        ]
    ];
    private Request $request;
    private EntityManagerInterface $entityManager;
    private FormUtils $formsUtil;
    private PasswordUtil $passwordUtil;
    private Security $security;
    private UserRepository $userRep;
    private string $sumbmittedRoute = '';


    #[Route('/', name: 'main')]
    public function index(
        Request $request,
        EntityManagerInterface $entityManager,
        PasswordUtil $passwordUtil,
        Security $security,
        UserRepository $userRep,
    ): Response {
        $this->request = $request;
        $this->entityManager = $entityManager;
        $this->passwordUtil = $passwordUtil;
        $this->security = $security;
        $this->userRep = $userRep;

        return $this->processThisController();;
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
            FormUtils::LOGIN => $this->formsUtil->getForms()[FormUtils::LOGIN]['form'],
            FormUtils::REGISTRATION => $this->formsUtil->getForms()[FormUtils::REGISTRATION]['form']
        ]);
    }

    private function processPOST(): Response
    {
        if ($this->sumbmittedRoute === FormUtils::REGISTRATION) {
            $this->formsUtil->setDataFromRequest();

            if ($this->formsUtil->isSubmittedAndValid()) {
                $orm = $this->formsUtil->getOrm(FormUtils::REGISTRATION);
                $user = $orm->getUser();
                $this->passwordUtil->hashPassword($user);

                $this->entityManager->persist($orm);
                $this->entityManager->flush();
                return $this->formsUtil->redirect($this);
            } else {
                $form = $this->formsUtil->getForms()[FormUtils::REGISTRATION]['form'];

                $errors = $form->getErrors(true);
                $dataToSendBack = [];

                foreach ($errors as $error) {
                    if ($error) {
                        $origin = $error->getOrigin();
                        $errorData['field'] = $origin->getName();
                        $errorData['value'] = $origin->getData();
                        $errorData['message'] = $error->getMessage();
                        $dataToSendBack[$origin->getName()][] = $errorData;
                    }
                }

                return $this->json([
                    'status' => false,
                    'data' => $dataToSendBack
                ]);
            }
        } elseif ($this->sumbmittedRoute === FormUtils::LOGIN) {
            //TODO create SESSION
            $this->formsUtil->setDataFromRequest();

            // $user = $this->formsUtil->getOrm(FormUtils::LOGIN);

            // $email = $user->getEmail();

            // $userFromBD = $this->userRep->getUserByEmail($email);

            // $password = $user->getPassword();
            // //check where password is valid
            // $isPassValid = $this->passwordUtil->isPasswordValid($userFromBD, $password);
            // if ($isPassValid) {
            //     $this->security->login($user);
            // }
        }

        return $this->renderMain();
    }

    public function getNewForm(string $className, mixed $orm): FormInterface
    {
        return $this->createForm($className, $orm);
    }

    public function goto(string $route, array $params = []): Response
    {
        return $this->redirectToRoute($route, $params);
    }

    public function getRequest()
    {
        return $this->request;
    }
}
