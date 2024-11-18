<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function getCount()
    {
        return $this->createQueryBuilder('u')
            ->select('COUNT(u.id)')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    public function getIds(): array
    {
        return $this->createQueryBuilder('u')
            ->select('u.id')
            ->getQuery()
            ->getScalarResult()
        ;
    }

    public function setReceiverNull(): void
    {
        $this->createQueryBuilder('u')
            ->update(User::class, 'u')
            ->set('u.receiver', null)
            ->getQuery()
            ->execute()
        ;
    }

    public function getCountWithoutReceiver()
    {
        return $this->createQueryBuilder('u')
            ->select('COUNT(u.id)')
            ->andWhere('u.receiver IS NULL')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    public function findPartialUsers(int $limit, int $offset)
    {
        return $this->createQueryBuilder('u')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }
}
