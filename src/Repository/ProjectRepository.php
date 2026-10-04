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
        // ordre de l'accueil : position, puis id pour départager les égalités
        $previous =
            $this->createQueryBuilder("p")
                ->andWhere(
                    "p.position < :position OR (p.position = :position AND p.id < :id)",
                )
                ->setParameter("position", $project->getPosition())
                ->setParameter("id", $project->getId())
                ->orderBy("p.position", "DESC")
                ->addOrderBy("p.id", "DESC")
                ->setMaxResults(1)
                ->getQuery()
                ->getOneOrNullResult() ??
            // premier projet : on boucle sur le dernier
            $this->findOneBy([], ["position" => "DESC", "id" => "DESC"]);

        $next =
            $this->createQueryBuilder("p")
                ->andWhere(
                    "p.position > :position OR (p.position = :position AND p.id > :id)",
                )
                ->setParameter("position", $project->getPosition())
                ->setParameter("id", $project->getId())
                ->orderBy("p.position", "ASC")
                ->addOrderBy("p.id", "ASC")
                ->setMaxResults(1)
                ->getQuery()
                ->getOneOrNullResult() ??
            // dernier projet : on boucle sur le premier
            $this->findOneBy([], ["position" => "ASC", "id" => "ASC"]);

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
