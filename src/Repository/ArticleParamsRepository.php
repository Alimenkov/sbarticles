<?php

namespace App\Repository;

use App\Entity\ArticleParams;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ArticleParams|null find($id, $lockMode = null, $lockVersion = null)
 * @method ArticleParams|null findOneBy(array $criteria, array $orderBy = null)
 * @method ArticleParams[]    findAll()
 * @method ArticleParams[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleParamsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ArticleParams::class);
    }

    // /**
    //  * @return ArticleParams[] Returns an array of ArticleParams objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ArticleParams
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
