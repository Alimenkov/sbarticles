<?php

namespace App\Repository;

use App\Entity\Module;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Module|null find($id, $lockMode = null, $lockVersion = null)
 * @method Module|null findOneBy(array $criteria, array $orderBy = null)
 * @method Module[]    findAll()
 * @method Module[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModuleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Module::class);
    }

    /**
     * @return Module[] Returns an array of Module objects
     */

    public function findAllUserModules(int $id): Query
    {
        return $this->findUserModulesQB($id)
            ->orderBy('m.modifiedAt', 'DESC')
            ->getQuery();
    }

    public function findModules(?int $limit = 0, ?int $userId = null, ?bool $onlyImg = null): array
    {
        if (!empty($userId)) {
            $qb = $this->findUserModulesQB($userId);
        } else {
            $qb = $this->findCommonModulesQB();
        }

        if (is_bool($onlyImg)) {
            $qb->andWhere("m.img = " . ($onlyImg ? 'true' : 'false'));
        }

        return $qb->orderBy('RANDOM()')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    protected function findUserModulesQB(int $id): QueryBuilder
    {
        return $this->findCommonModulesQB()
            ->orWhere('o.id = :val')
            ->setParameter('val', $id);
    }

    protected function findCommonModulesQB(): QueryBuilder
    {
        return $this->getModuleWithOwnerQB()
            ->andWhere('o.id is NULL');
    }

    protected function getModuleWithOwnerQB(): QueryBuilder
    {
        return $this->createQueryBuilder('m')
            ->leftJoin('m.owner', 'o');
    }


    /*
    public function findOneBySomeField($value): ?Module
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
