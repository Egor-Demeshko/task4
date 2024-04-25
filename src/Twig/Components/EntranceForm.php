<?php

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class EntranceForm
{
    public const TYPE_LOGIN = "login";
    public const TYPE_PASSWORD = "password";
    public $type = '';
    public $title = '';
    public $hide = '';
}
