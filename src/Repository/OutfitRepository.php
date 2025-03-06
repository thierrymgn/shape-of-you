<?php

namespace App\Repository;

use App\Entity\Outfit;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Outfit>
 */
class OutfitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Outfit::class);
    }

    /**
     * @return array<Outfit>
     */
    public function search(string $query, User $user): array
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.name LIKE :query OR o.description LIKE :query OR o.occasion LIKE :query')
            ->andWhere('o.customer = :user')
            ->setParameter('query', '%'.$query.'%')
            ->setParameter('user', $user)
            ->orderBy('o.name', 'ASC')
            ->setMaxResults(20)
            ->getQuery()
            ->getResult();
    }

    /**
     * Recherche de tenues par mot-clé (celles de l'utilisateur et les tenues publiques).
     *
     * @param string $query Terme de recherche
     * @param User   $user  Utilisateur actuel
     *
     * @return array<Outfit>
     */
    public function searchAllAccessible(string $query, User $user): array
    {
        $qb = $this->createQueryBuilder('o');

        return $qb->andWhere('o.name LIKE :query OR o.description LIKE :query OR o.occasion LIKE :query')
            ->andWhere(
                $qb->expr()->orX(
                    $qb->expr()->eq('o.customer', ':user'),
                    $qb->expr()->andX(
                        $qb->expr()->neq('o.customer', ':user'),
                        $qb->expr()->eq('o.isPublic', ':isPublic')
                    )
                )
            )
            ->setParameter('query', '%'.$query.'%')
            ->setParameter('user', $user)
            ->setParameter('isPublic', true)
            ->orderBy('o.name', 'ASC')
            ->setMaxResults(50)
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return Outfit[] Returns an array of Outfit objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('o')
    //            ->andWhere('o.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('o.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Outfit
    //    {
    //        return $this->createQueryBuilder('o')
    //            ->andWhere('o.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
