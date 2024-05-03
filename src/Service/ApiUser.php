<?php

namespace App\Service;

use App\Repository\UserRepository;

class ApiUser
{
    public function __construct(private UserRepository $repository)
    {
    }
}
