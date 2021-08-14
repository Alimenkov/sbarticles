<?php

namespace App\Controller;


use App\Entity\Subscription;
use App\Repository\SubscriptionRepository;
use App\Repository\UserSubscriptionRepository;
use App\Service\AddUserSubscription;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("IS_VERIFIED")
 */
class SubscriptionController extends AbstractController
{
    /**
     * @Route("/subscription", name="app_subscription_list")
     *
     */
    public function subscription_list(SubscriptionRepository $subscriptionRepository, UserSubscriptionRepository $userSubscriptionRepository): Response
    {

        $subscriptions = $subscriptionRepository->findAll();

        $subscription = $userSubscriptionRepository->findActualUserSubType($this->getUser());

        return $this->render('subscription/list.html.twig',
            [
                'subscriptions' => $subscriptions,
                'current' => $subscription
            ]
        );
    }


    /**
     * @Route("/subscription/add/{id<\d+>}", name="app_user_subscription_add")
     */
    public function subscription_add(Subscription $subscription, AddUserSubscription $addUserSubscription): Response
    {

        $addUserSubscription->addSubscription($subscription);

        return $this->redirectToRoute('app_subscription_list');
    }
}
