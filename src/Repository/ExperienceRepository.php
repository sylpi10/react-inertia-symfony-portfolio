<?php

namespace App\Repository;

use App\Entity\Experience;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Experience>
 */
class ExperienceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Experience::class);
    }

    /**
     * Étapes dans l'ordre de la timeline, avec leurs projets chargés en une requête.
     *
     * @return list<Experience>
     */
    public function findForTimeline(): array
    {
        return $this->createQueryBuilder('e')
            ->leftJoin('e.projects', 'p')
            ->addSelect('p')
            ->orderBy('e.position', 'ASC')
            ->addOrderBy('e.id', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
