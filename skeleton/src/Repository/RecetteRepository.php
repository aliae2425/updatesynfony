<?php

namespace App\Repository;

use App\Entity\Recette;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

/**
 * @extends ServiceEntityRepository<Recette>
 */
class RecetteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recette::class);
    }

    /**
     * @return Recette[] Returns an array of Recette objects
     */
    public function findWithDurationLowerThan(int $duration): array
    {
        return $this->createQueryBuilder("r")
            ->andWhere("r.duration < :duration")
            ->setParameter("duration", $duration)
            ->orderBy("r.duration", "ASC")
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Recette[] Returns an array of Recette objects
     */
    public function findAllWithCategory(): array
    {
        return $this->createQueryBuilder("r")
            ->select("r", "c")
            ->leftJoin("r.category", "c")
            ->getQuery()
            ->getResult();
    }


    public function paginateRecipies(int $page = 1, int $limite = 10): Paginator
    {
        return new Paginator($this
            ->createQueryBuilder("r")
            ->setMaxResults($limite)
            ->setFirstResult(($page - 1 )* $limite)
            ->getQuery()
            ->setHint(Paginator::HINT_ENABLE_DISTINCT, false)
        );
    }
}
