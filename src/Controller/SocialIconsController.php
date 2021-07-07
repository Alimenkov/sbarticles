<?php

namespace App\Controller;

use App\Repository\SocialRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\HttpFoundation\Response;

class SocialIconsController extends AbstractController
{
    public function list(SocialRepository $socialRepository, AdapterInterface $filesystemAdapter): Response
    {

        $socialIcons = $filesystemAdapter->getItem('SocialIconsList');

        if (!$socialIcons->isHit()) {

            $socialIcons->expiresAfter(3600);

            $socialIcons->set($socialRepository->findAll());

            $filesystemAdapter->save($socialIcons);
        }

        return $this->render('partials/list.html.twig', [
            'icons' => $socialIcons->get()
        ]);
    }
}
