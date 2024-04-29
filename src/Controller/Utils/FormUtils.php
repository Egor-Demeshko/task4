<?php

declare(strict_types=1);

namespace App\Controller\Utils;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\Form\Test\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FormUtils
{
    private array $forms = [];
    private array $orms = [];
    const LOGIN = "login";
    const REGISTRATION = "registration";

    private ?Request $request = null;
    private ?string $sumbmittedRoute = null;

    public function __construct(array $formsOption, AbstractController $controller)
    {
        $forms = [];

        if ($controller->getRequest()) {
            $this->request = $controller->getRequest();
        }

        foreach ($formsOption as $option) {
            [
                'type' => $type,
                'className' => $className,
                'orm' => $orm,
                'redirectTo' => $redirectTo
            ] = $option;

            $this->orms[$type] = new $orm();

            $forms[$type]['form'] = $controller->getNewForm(
                $className,
                $this->orms[$type]
            );
            $forms[$type]['redirectTo'] = $redirectTo;
        }

        $this->forms = $forms;
    }

    public function isSubmittedAndValid(): bool
    {
        $result = false;
        $formName = $this->sumbmittedRoute;

        if (isset($this->forms[$formName])) {
            $result = $this->checkForm($this->forms[$formName], $formName);
        }


        return $result;
    }


    public function setRegistrationDataFromRequest()
    {
        $formName = $this->sumbmittedRoute;
        $form = $this->forms[$formName]['form'] ?? null;
        $orm = $this->orms[$formName] ?? null;

        if (isset($form)) {
            $form->handleRequest($this->request);
        }
    }

    public function redirect(AbstractController $controller): Response
    {
        foreach ($this->forms as $formData) {
            if ($formData['canRedirect']) {
                return $controller->goto($formData['redirectTo']);
            }
        }
    }

    public function getForms(): array
    {
        return $this->forms;
    }

    public function getOrm(string $name): mixed
    {
        return $this->orms[$name] ?? null;
    }


    public function setSubmittedRoute(string $route): void
    {
        $this->sumbmittedRoute = $route;
    }

    private function checkForm(&$formData, string $type): bool
    {
        $form = $formData['form'];

        if ($form->isSubmitted() && $form->isValid()) {

            $formData['canRedirect'] = true;
            return true;
        }

        return false;
    }
}
