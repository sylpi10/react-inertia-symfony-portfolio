<?php

namespace App\Repository;

use App\Entity\Project;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Project>
 */
class ProjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Project::class);
    }

    /**
     * Projets précédent et suivant dans l'ordre d'affichage de l'accueil,
     * en boucle : le premier et le dernier projet se suivent.
     *
     * @return array{previous: ?Project, next: ?Project}
     */
    public function findAdjacent(Project $project): array
    {
        $previous =
            $this->createQueryBuilder("p")
                ->andWhere("p.position < :position")
                ->setParameter("position", $project->getPosition())
                ->orderBy("p.position", "ASC")
                ->setMaxResults(1)
                ->getQuery()
                ->getOneOrNullResult() ??
            $this->findOneBy([], ["id" => "DESC"]);

        $next =
            $this->createQueryBuilder("p")
                ->andWhere("p.id > :id")
                ->setParameter("id", $project->getId())
                ->orderBy("p.id", "ASC")
                ->setMaxResults(1)
                ->getQuery()
                ->getOneOrNullResult() ?? $this->findOneBy([], ["id" => "ASC"]);

        // un seul projet en base : pas de navigation vers lui-même
        return [
            "previous" => $previous === $project ? null : $previous,
            "next" => $next === $project ? null : $next,
        ];
    }

    //    /**
    //     * @return Project[] Returns an array of Project objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Project
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
