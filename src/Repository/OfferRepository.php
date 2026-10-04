<?php

namespace App\Repository;

use App\Entity\Offer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Offer>
 */
class OfferRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Offer::class);
    }

    /**
     * Services dans l'ordre d'affichage, avec leurs projets chargés en une requête.
     *
     * @return list<Offer>
     */
    public function findForHome(): array
    {
        return $this->createQueryBuilder('o')
            ->leftJoin('o.projects', 'p')
            ->addSelect('p')
            ->orderBy('o.position', 'ASC')
            ->addOrderBy('o.id', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
