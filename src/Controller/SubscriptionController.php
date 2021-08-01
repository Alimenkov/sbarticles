<?php

namespace App\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class SubscriptionController extends AbstractController
{
    /**
     * @Route("/subscription", name="app_subscribtion_list")
     * @IsGranted("IS_VERIFIED")
     */
    public function subscription_list(): Response
    {

        return $this->render('subscription/list.html.twig');
    }
}
