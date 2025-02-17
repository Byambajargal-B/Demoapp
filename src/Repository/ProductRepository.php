<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * Find products by the current authenticated user.
     *
     * @param int $userId The user's ID
     * @return Product[] Returns an array of Product objects
     */
    public function findByUserId(int $userId)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.user_id = :user_id')
            ->setParameter('user_id', $userId)
            ->getQuery()
            ->getResult();
    }
}
