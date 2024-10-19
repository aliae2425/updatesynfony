<?php

namespace App\Repository;

use App\Entity\Recette;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

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
}
