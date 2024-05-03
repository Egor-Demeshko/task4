<?php

namespace App\Service;

use App\Repository\UserRepository;

class ApiUser
{
    public function __construct(private UserRepository $repository)
    {
    }

    public function getUsers()
    {
        $data = $this->repository->getAllUsers();

        $data = $this->prepareData($data);

        return $data;
    }


    /**
     * @description This handler made cause with built config and 
     * user entity realization we got "A circular reference has been detected when serializing the object"
     */
    private function prepareData(array $data): array
    {
        $returnData = [];

        foreach ($data as $userObj) {
            $inner = [];
            $inner['email'] = $userObj->getEmail() ?? null;
            $inner['id'] = $userObj->getId();
            $userDetails = $userObj->getUserDetails() ?? null;

            if ($userDetails) {
                $inner['name'] = $userDetails->getName() ?? null;
                $inner['last_loggined_at'] = $userDetails->getLastLoginedAt() ?? null;
                $inner['registrated_at'] = $userDetails->getRegistratedAt() ?? null;
                $inner['status'] = $userDetails->getStatus() ?? null;
            }

            $returnData[] = $inner;
        }

        return $returnData;
    }
}
