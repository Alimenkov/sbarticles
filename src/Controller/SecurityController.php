<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\LoginFormType;
use App\Form\Model\UserLoginFormModel;
use App\Form\RegistrationFormType;
use App\Security\Utils\AuthenticationRemeberMeUtils;
use App\Service\ChangeEmail;
use App\Service\ChangeProfile;
use App\Service\Email\SendUserEmailConfirmation;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationRemeberMeUtils $authenticationUtils, Request $request): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        $rememberMe = $authenticationUtils->getRememberMe();

        $userLoginFormModel = new UserLoginFormModel();

        $userLoginFormModel->email = $lastUsername;

        $userLoginFormModel->_remember_me = $rememberMe;

        $form = $this->createForm(LoginFormType::class, $userLoginFormModel);

        return $this->render('security/login.html.twig',
            [
                'loginForm' => $form->createView(),
                'error' => $error
            ]
        );
    }

    /**
     * @Route("/register", name="app_register")
     */
    public function register(SendUserEmailConfirmation $emailConfirmation, Request $request, UserPasswordHasherInterface $userPasswordHasher): Response
    {

        /** @var PasswordAuthenticatedUserInterface $user */
        $user = new User();

        $form = $this->createForm(RegistrationFormType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $plainPasswd = $form->get('plainPassword')->getData();

            $confirmPasswd = $form->get('confirmPassword')->getData();

            if (strcasecmp($plainPasswd, $confirmPasswd) != 0) {

                $errorPasswd = new FormError('Пароли не совпадают');

                $form->addError($errorPasswd);

            } else {

                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();

                $emailConfirmation->send($user);

                $this->addFlash(
                    'security_message',
                    'На вашу почту отправлено письмо для подтверждения регистрации'
                );
            }
        }

        return $this->render('security/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/account-verification", name="app_account_verification")
     * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
     */
    public function accountVerification(SendUserEmailConfirmation $emailConfirmation, UrlGeneratorInterface $urlGenerator): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if ($user->isVerified()) {
            $text = sprintf('Ваш аккаунт уже верифицирован <a href="%s">Перейти на главную страницу</a>', $urlGenerator->generate('app_mainpage'));
        } else {
            $emailConfirmation->send($user);

            $text = 'На ваш e-mail выслано письмо для верификации аккаунта!';
        }

        return $this->render('security/account_verification.html.twig',
            ['text' => $text]
        );
    }

    /**
     * @Route("/rigister-confirmation", name="app_register_confirmation")
     * @isGranted ("IS_AUTHENTICATED_FULLY")
     */
    public function registerConfirmation(Request $request, VerifyEmailHelperInterface $helper)
    {
        $user = $this->getUser();

        // Do not get the User's Id or Email Address from the Request object

        try {
            $helper->validateEmailConfirmation($request->getUri(), $user->getId(), $user->getEmail());
        } catch (VerifyEmailExceptionInterface $e) {
            $this->addFlash('verify_email_error', $e->getReason());

            return $this->redirectToRoute('app_register');
        }

        // Mark your user as verified. e.g. switch a User::verified property to true

        $user->setIsVerified(true);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        $this->addFlash('success', 'Ваш e-mail адрес подтверждён.');

        return $this->redirectToRoute('app_mainpage');
    }

    /**
     * @Route("/profile", name="app_profile")
     * @isGranted ("IS_AUTHENTICATED_FULLY")
     */
    public function profile(ChangeProfile $changeProfile): Response
    {
        $form = $changeProfile->makeForm();

        if ($changeProfile->save()) {
            return $this->redirectToRoute('app_profile');
        }

        return $this->render('security/profile.html.twig', [
            'profileForm' => $form->createView(),
        ]);
    }


    /**
     * @Route("/change-email", name="app_change_email_confirmation")
     * @isGranted ("IS_AUTHENTICATED_FULLY")
     */
    public function changeEmail (ChangeEmail $changeEmail, Request $request): Response
    {
        $changeEmail->tryToChangeEmail($request);

        return $this->redirectToRoute('app_profile');
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
