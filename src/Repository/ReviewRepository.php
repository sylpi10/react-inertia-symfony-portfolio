<?php

namespace App\Repository;

use App\Entity\Review;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Review>
 */
class ReviewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Review::class);
    }

    /**
     * Avis publiables (validés en admin), les plus récents d'abord, avec leurs
     * projets chargés dans la même requête.
     *
     * @return list<Review>
     */
    public function findValidated(): array
    {
        return $this->createQueryBuilder("r")
            ->addSelect("p")
            ->leftJoin("r.projects", "p")
            ->andWhere("r.validated = true")
            ->orderBy("r.createdAt", "DESC")
            ->addOrderBy("p.id", "ASC")
            ->getQuery()
            ->getResult();
    }
}
