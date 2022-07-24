<?php

namespace App\Repository;

use App\Entity\Subscription;
use App\Entity\UserSubscription;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserSubscription|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserSubscription|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserSubscription[]    findAll()
 * @method UserSubscription[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserSubscriptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserSubscription::class);
    }

    // /**
    //  * @return UserSubscription[] Returns an array of UserSubscription objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    public function findActualUserSub($user): ?UserSubscription
    {
        $result = $this->createQueryBuilder('u')
            ->leftJoin('u.subscription', 's')
            ->andWhere('u.owner = :user and u.expiredAt >= :date')
            ->setParameters(['user' => $user, 'date' => new \DateTimeImmutable()])
            ->orderBy('s.price', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();

        return !empty($result) ? $result[0] : null;
    }

    public function findActualUserSubType($user): ?Subscription
    {
        $userSub = $this->findActualUserSub($user);

        return !empty($userSub) ? $userSub->getSubscription() : null;
    }
}
