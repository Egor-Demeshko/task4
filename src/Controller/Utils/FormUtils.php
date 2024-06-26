<?php

declare(strict_types=1);

namespace App\Controller\Utils;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class FormUtils
{
    private array $forms = [];
    private array $orms = [];
    const LOGIN = "user";
    const REGISTRATION = "user_details";

    private ?Request $request = null;
    private ?string $sumbmittedRoute = null;

    public function __construct(array $formsOption, AbstractController $controller)
    {

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

            $this->forms[$type]['form'] = $controller->getNewForm(
                $className,
                $this->orms[$type]
            );
            $this->forms[$type]['redirectTo'] = $redirectTo;
        }
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


    public function setDataFromRequest()
    {
        $formName = $this->sumbmittedRoute;
        $form = $this->forms[$formName]['form'] ?? null;

        if (isset($form)) {
            $this->forms[$formName]['form'] = $form->handleRequest($this->request);
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
