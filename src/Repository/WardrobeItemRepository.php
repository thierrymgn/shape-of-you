<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\WardrobeItem;
use App\Enum\WardrobeStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WardrobeItem>
 */
class WardrobeItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WardrobeItem::class);
    }

    /**
     * @param string $query
     * @param User $user
     * @return array<WardrobeItem>
     */
    public function search(string $query, User $user): array
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.name LIKE :query OR w.description LIKE :query OR w.brand LIKE :query OR w.color LIKE :query')
            ->andWhere('w.customer = :user')
            ->setParameter('query', '%' . $query . '%')
            ->setParameter('user', $user)
            ->orderBy('w.name', 'ASC')
            ->setMaxResults(20)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param string $query
     * @param User $user
     * @return array<WardrobeItem>
     */
    public function searchAllAccessible(string $query, User $user): array
    {
        $qb = $this->createQueryBuilder('w');

        return $qb->andWhere('w.name LIKE :query OR w.description LIKE :query OR w.brand LIKE :query OR w.color LIKE :query')
            ->andWhere(
                $qb->expr()->orX(
                    $qb->expr()->eq('w.customer', ':user'),
                    $qb->expr()->andX(
                        $qb->expr()->neq('w.customer', ':user'),
                        $qb->expr()->eq('w.status', ':active_status')
                    )
                )
            )
            ->setParameter('query', '%' . $query . '%')
            ->setParameter('user', $user)
            ->setParameter('active_status', WardrobeStatus::ACTIVE)
            ->orderBy('w.name', 'ASC')
            ->setMaxResults(50)
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return WardrobeItem[] Returns an array of WardrobeItem objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('w')
    //            ->andWhere('w.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('w.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?WardrobeItem
    //    {
    //        return $this->createQueryBuilder('w')
    //            ->andWhere('w.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
