<?php

namespace App\Repository;

use App\Entity\UserDetails;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserDetails>
 *
 * @method UserDetails|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserDetails|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserDetails[]    findAll()
 * @method UserDetails[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserDetailsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserDetails::class);
    }


    public function setLastLogginedAt(int $user_id, DateTime $time): void
    {
        $queryBuilder = $this->createQueryBuilder('ud')
            ->update(UserDetails::class, 'ud')
            ->set('ud.last_logined_at', ':last')
            ->where('ud.user_id=:user_id')
            ->setParameter('last', $time)
            ->setParameter('user_id', $user_id);

        $query = $queryBuilder->getQuery();
        $result = $query->execute();
    }

    public function setStatusBlock(array $ids): bool
    {
        $queryBuilder = $this->createQueryBuilder('ud')
            ->update(UserDetails::class, 'ud')
            ->set('ud.status', ':block')
            ->where('ud.user_id IN (:ids)')
            ->setParameter('ids', $ids)
            ->setParameter('block', 'block');

        $query = $queryBuilder->getQuery();
        $result = $query->execute();

        if ($result === 0) {
            return false;
        }

        return true;
    }

    public function setStatusUnBlock(array $ids)
    {
        $queryBuilder = $this->createQueryBuilder('ud')
            ->update(UserDetails::class, 'ud')
            ->set('ud.status', ':active')
            ->where('ud.user_id IN (:ids)')
            ->setParameter('ids', $ids)
            ->setParameter('active', 'active');

        $query = $queryBuilder->getQuery();
        $result = $query->execute();

        if ($result === 0) {
            return false;
        }

        return true;
    }


    //    /**
    //     * @return UserDetails[] Returns an array of UserDetails objects
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

    //    public function findOneBySomeField($value): ?UserDetails
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
