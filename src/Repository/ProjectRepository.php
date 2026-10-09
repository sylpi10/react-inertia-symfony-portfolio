<?php

namespace App\Repository;

use App\Entity\Project;
use App\Enum\Audience;
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
     * Tous les projets, dans l'ordre de la page d'un public.
     *
     * @return list<Project>
     */
    public function findOrderedFor(Audience $audience): array
    {
        return $this->findBy([], [self::orderField($audience) => "ASC", "id" => "ASC"]);
    }

    /**
     * Projets encore sans avis (validé ou en attente), ceux proposés dans le
     * formulaire d'avis ; limités à $ids si fournis.
     *
     * @param list<int>|null $ids
     *
     * @return list<Project>
     */
    public function findWithoutReview(?array $ids = null): array
    {
        $criteria = ["review" => null];
        if (null !== $ids) {
            $criteria["id"] = $ids;
        }

        return $this->findBy($criteria, ["teamPosition" => "ASC", "id" => "ASC"]);
    }

    /**
     * Projets précédent et suivant dans l'ordre de la page d'un public,
     * en boucle : le premier et le dernier projet se suivent.
     *
     * @return array{previous: ?Project, next: ?Project}
     */
    public function findAdjacent(Project $project, Audience $audience): array
    {
        $field = self::orderField($audience);
        $position = Audience::Team === $audience
            ? $project->getTeamPosition()
            : $project->getPosition();

        // ordre de la page, puis id pour départager les égalités
        $previous =
            $this->createQueryBuilder("p")
                ->andWhere(
                    "p.$field < :position OR (p.$field = :position AND p.id < :id)",
                )
                ->setParameter("position", $position)
                ->setParameter("id", $project->getId())
                ->orderBy("p.$field", "DESC")
                ->addOrderBy("p.id", "DESC")
                ->setMaxResults(1)
                ->getQuery()
                ->getOneOrNullResult() ??
            // premier projet : on boucle sur le dernier
            $this->findOneBy([], [$field => "DESC", "id" => "DESC"]);

        $next =
            $this->createQueryBuilder("p")
                ->andWhere(
                    "p.$field > :position OR (p.$field = :position AND p.id > :id)",
                )
                ->setParameter("position", $position)
                ->setParameter("id", $project->getId())
                ->orderBy("p.$field", "ASC")
                ->addOrderBy("p.id", "ASC")
                ->setMaxResults(1)
                ->getQuery()
                ->getOneOrNullResult() ??
            // dernier projet : on boucle sur le premier
            $this->findOneBy([], [$field => "ASC", "id" => "ASC"]);

        // un seul projet en base : pas de navigation vers lui-même
        return [
            "previous" => $previous === $project ? null : $previous,
            "next" => $next === $project ? null : $next,
        ];
    }

    // champ d'ordre de chaque page : teamPosition pour l'accueil
    private static function orderField(Audience $audience): string
    {
        return Audience::Team === $audience ? "teamPosition" : "position";
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
