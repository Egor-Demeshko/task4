<?php

namespace App\Service;

use App\Repository\UserDetailsRepository;
use App\Repository\UserRepository;
use App\Service\Cleaner;
use Doctrine\ORM\EntityManagerInterface;
use Throwable;

class ApiUser
{
    public function __construct(
        private UserRepository $repository,
        private UserDetailsRepository $userDetailsRepository,
        private Cleaner $cleaner,
        private EntityManagerInterface $em
    ) {
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

    public function clearIncoming(string $unparsedData)
    {
        $data = $this->cleaner->clearString($unparsedData);
        $ids = json_decode($data);
        $this->cleaner->insureIntInArray($ids);
        return $ids;
    }

    public function setStatusBlocked(array $ids): bool
    {
        return $this->userDetailsRepository->setStatusBlock($ids);
    }

    public function setStatusUnBlocked(array $ids): bool
    {
        return $this->userDetailsRepository->setStatusUnBlock($ids);
    }

    public function delete(array $ids): bool
    {
        $this->em->beginTransaction();
        try {
            $result1 = $this->userDetailsRepository->delete($ids, $this->em);
            $result2 = $this->repository->delete($ids, $this->em);
        } catch (Throwable $e) {
            $this->em->rollback();
            return false;
        }

        if ($result1 && $result2) {
            return true;
        } else {
            $this->em->rollback();
            return false;
        }
    }
}
