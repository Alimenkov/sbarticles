<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * @Route("/", name="app_mainpage")
     */
    public function mainpage(): Response
    {
        return $this->render('mainpage.html.twig');
    }

    /**
     * @Route("/article-create", name="app_article_create")
     */
    public function article_create(): Response
    {


        return $this->render('articles/create.html.twig');
    }
}
