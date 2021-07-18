<?php


namespace App\Service\Email;


use App\Repository\UserRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\ResetPassword\Exception\ResetPasswordExceptionInterface;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;

class ProcessingSendingPasswordResetEmail
{

    private UserRepository $userRepository;
    private MailerInterface $mailer;
    private ResetPasswordHelperInterface $resetPasswordHelper;
    private SessionInterface $session;
    private TranslatorInterface $translator;

    public function __construct(
        UserRepository $userRepository,
        MailerInterface $mailer,
        ResetPasswordHelperInterface $resetPasswordHelper,
        RequestStack $requestStack,
        TranslatorInterface $translator
    )
    {
        $this->userRepository = $userRepository;
        $this->mailer = $mailer;
        $this->resetPasswordHelper = $resetPasswordHelper;
        $this->session = $requestStack->getSession();
        $this->translator = $translator;
    }

    public function send(string $email)
    {
        $resetToken = false;

        $user = $this->userRepository->findOneBy([
            'email' => $email,
        ]);

        // Do not reveal whether a user account was found or not.
        if ($user) {

            try {

                $resetToken = $this->resetPasswordHelper->generateResetToken($user);

                $email = (new TemplatedEmail())
                    ->to(new Address($user->getEmail(), $user->getName()))
                    ->subject('Восстановление пароля')
                    ->htmlTemplate('emails/reset-password.html.twig')
                    ->context([
                        'resetToken' => $resetToken,
                    ]);

                $this->session->getFlashBag()->add(
                    'security_message',
                    'На вашу почту отправлена ссылка для смены пароля!'
                );

                $this->mailer->send($email);

            } catch (ResetPasswordExceptionInterface $e) {
                $this->session->getFlashBag()->add(
                    'reset_password_error',
                    $this->translator->trans($e->getReason())
                );
            }
        }

        return $resetToken;
    }
}