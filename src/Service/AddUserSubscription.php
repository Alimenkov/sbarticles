<?php


namespace App\Service;


use App\Entity\Subscription;
use App\Entity\UserSubscription;
use App\Repository\UserSubscriptionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Security;

class AddUserSubscription
{

    private UserSubscriptionRepository $userSubscriptionRepository;
    private EntityManagerInterface $em;
    private Security $security;
    private RequestStack $requestStack;

    public function __construct(
        UserSubscriptionRepository $userSubscriptionRepository,
        EntityManagerInterface $em,
        Security $security,
        RequestStack $requestStack
    )
    {

        $this->userSubscriptionRepository = $userSubscriptionRepository;
        $this->em = $em;
        $this->security = $security;
        $this->requestStack = $requestStack;
    }

    public function addSubscription(Subscription $subscription): void
    {
        $curUser = $this->security->getUser();

        $userSub = $this->userSubscriptionRepository->findActualUserSubType($curUser);

        if (!empty($userSub) && $userSub->getPrice() >= $subscription->getPrice()) {

            $this->requestStack->getSession()->getFlashBag()->add(
                'error_message',
                'Нельзя оформить подписку такого же уровня или уровня ниже'
            );

        } else {

            $date = new \DateTimeImmutable();

            $dateExpired = $date->add(new \DateInterval('P2W'));

            $userSub = (new UserSubscription())
                ->setOwner($curUser)
                ->setSubscription($subscription)
                ->setCreatedAt($date)
                ->setModifiedAt($date)
                ->setExpiredAt($dateExpired);

            $this->em->persist($userSub);
            $this->em->flush();

            $this->requestStack->getSession()->getFlashBag()->add(
                'template_message',
                sprintf('Подписка оформлена до %s', $dateExpired->format('d.m.Y'))
            );
        }
    }
}