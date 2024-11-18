<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
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

    public function allSetRandomNumber(): void
    {
        $count = $this->getCount();

        $sql = 'UPDATE user u SET u.serial_number = FLOOR(RAND() * :salt)';
        $this->getEntityManager()
            ->createNativeQuery($sql, new ResultSetMapping())
            ->setParameter(':salt', $count * $count)
            ->execute()
        ;
    }

    public function findPartialOrderBySerialNumber(int $limit, int $offset)
    {
        return $this->createQueryBuilder('u')
            ->orderBy('u.serialNumber', 'ASC')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }
}
