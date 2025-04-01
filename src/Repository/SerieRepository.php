<?php
namespace App\Repository;

use App\Entity\Serie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class SerieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Serie::class);
    }

    public function findFilteredSeries(string $search, string $status, string $genre, string $sort, string $direction, int $page, int $limit): array
    {
        $queryBuilder = $this->createQueryBuilder('s');
    
        if ($search) {
            $queryBuilder->andWhere('s.name LIKE :search OR s.overview LIKE :search')
                ->setParameter('search', '%' . $search . '%');
        }
    
        if ($status) {
            $queryBuilder->andWhere('s.status = :status')
                ->setParameter('status', $status);
        }
    
        if ($genre) {
            $queryBuilder->andWhere('s.genres LIKE :genre')
                ->setParameter('genre', '%' . $genre . '%');
        }
    
        $allowedSortFields = ['id', 'name', 'vote', 'popularity', 'first_air_date', 'last_air_date'];
        if (in_array($sort, $allowedSortFields)) {
            $queryBuilder->orderBy('s.' . $sort, $direction);
        } else {
            $queryBuilder->orderBy('s.id', $direction);
        }
    
        $queryBuilder->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);
    
        return $queryBuilder->getQuery()->getResult();
    }

    public function countFilteredSeries(string $search, string $status, string $genre): int
    {
        $queryBuilder = $this->createQueryBuilder('s')
            ->select('COUNT(s.id)');
    
        if ($search) {
            $queryBuilder->andWhere('s.name LIKE :search OR s.overview LIKE :search')
                ->setParameter('search', '%' . $search . '%');
        }
    
        if ($status) {
            $queryBuilder->andWhere('s.status = :status')
                ->setParameter('status', $status);
        }
    
        if ($genre) {
            $queryBuilder->andWhere('s.genres LIKE :genre')
                ->setParameter('genre', '%' . $genre . '%');
        }
    
        return (int) $queryBuilder->getQuery()->getSingleScalarResult();
    }


}