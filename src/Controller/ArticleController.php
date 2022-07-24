<?php

namespace App\Controller;

use App\Entity\Module;
use App\Form\ArticleFormType;
use App\Form\Model\ArticleFormModel;
use App\Form\ModuleFormType;
use App\Repository\ModuleRepository;
use App\Service\ArticleCreate;
use App\Service\CheckModuleImg;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormErrorIterator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ArticleController extends AbstractController
{
    /**
     * @Route("/", name="app_mainpage")
     */
    public function mainpage(): Response
    {
        $some =567;
        echo $some;
        xdebug_var_dump($this);
        $some= 34;
        echo $some;
        return $this->render('mainpage.html.twig');
    }

    /**
     * @Route("/article-create", name="app_article_create")
     */
    public function article_create(ArticleCreate $articleCreate): Response
    {
        $articleForm = new ArticleFormModel();

        $form = $this->createForm(ArticleFormType::class, $articleForm);

        $articleCreate->make($form);

        return $this->render('articles/create.html.twig', [
            'articleForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/article-modules", name="app_article_modules")
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function article_modules(ModuleRepository $moduleRepository, Request $request, PaginatorInterface $paginator, CheckModuleImg $checkModuleImg): Response
    {
        $user = $this->getUser();

        $module = new Module();

        $form = $this->createForm(ModuleFormType::class, $module);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            if ($form->isValid()) {

                $module
                    ->setOwner($user)
                    ->setModifiedAt(new \DateTime())
                    ->setImg($checkModuleImg->check($module->getContent()));

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($module);
                $entityManager->flush();

                $this->addFlash(
                    'info_message',
                    'Модуль успешно добавлен'
                );

                return $this->redirectToRoute('app_article_modules');

            } else {

                /** @var FormErrorIterator $errors */

                $errorSize = ($form->getErrors(true))->count();

                if ($errorSize > 0)

                    $this->addFlash(
                        'error_message',
                        'Ошибка добавления модуля'
                    );
            }
        }

        $modules = $moduleRepository->findAllUserModules($user->getId());

        $pagination = $paginator->paginate(
            $modules, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            $request->query->getInt('size', 10) /*limit per page*/
        );

        return $this->render(
            'articles/modules.html.twig',
            [
                'modules' => $pagination,
                'form' => $form->createView()
            ]
        );
    }

    /**
     * @Route("/article-module-delete/{id<\d+>}", name="app_article_modules_delete")
     * @IsGranted("CAN_DELETE", subject="module")
     */
    public function article_modules_delete(Module $module): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($module);
        $entityManager->flush();

        return $this->json(['success' => true]);
    }

}
