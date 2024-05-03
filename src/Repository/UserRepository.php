<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }


    public function isEmailUnique($email): bool
    {
        //build query request
        $queryBuilder = $this->createQueryBuilder('u')
            ->select('u.email')
            ->where('u.email = :email')
            ->setParameter('email', $email);

        $query = $queryBuilder->getQuery();
        $result = $query->setMaxResults(1)->getOneOrNullResult();

        if (isset($result['email'])) {
            true;
        }

        return false;
    }


    public function getUserByEmail(string $email): mixed
    {
        $queryBuilder = $this->createQueryBuilder('u')
            ->select('u')
            ->where('u.email = :email')
            ->setParameter('email', $email);

        $query = $queryBuilder->getQuery();
        $result = $query->setMaxResults(1)->getOneOrNullResult();
        return $result;
    }

    public function getAllUsers(): array
    {
        $query = $this->createQueryBuilder('u')
            ->select('u', 'ud')
            ->leftJoin('u.user_details', 'ud')
            ->getQuery();
        $result = $query->getResult();

        return $result;
    }

    public function delete(array $ids, EntityManagerInterface $em)
    {
        $queryBuilder = $em->createQueryBuilder('u')
            ->delete(User::class, 'u')
            ->where('u.id in (:ids)')
            ->setParameter('ids', $ids);

        $query = $queryBuilder->getQuery();
        $result = $query->getResult();
        if ($result === 0) {
            return false;
        } else {
            return true;
        }
    }

    //    /**
    //     * @return User[] Returns an array of User objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('u.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?User
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
