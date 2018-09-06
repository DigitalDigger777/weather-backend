<?php

namespace App\Repository;

use App\Entity\WeatherCache;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method WeatherCache|null find($id, $lockMode = null, $lockVersion = null)
 * @method WeatherCache|null findOneBy(array $criteria, array $orderBy = null)
 * @method WeatherCache[]    findAll()
 * @method WeatherCache[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WeatherCacheRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, WeatherCache::class);
    }

//    /**
//     * @return WeatherCache[] Returns an array of WeatherCache objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('w.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?WeatherCache
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
