<?php


namespace App\Service\Email;


use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;


class SendUserEmailConfirmation
{
    private VerifyEmailHelperInterface $helper;
    private MailerInterface $mailer;

    public function __construct(VerifyEmailHelperInterface $helper, MailerInterface $mailer)
    {

        $this->helper = $helper;
        $this->mailer = $mailer;
    }

    public function send(PasswordAuthenticatedUserInterface $user)
    {
        $signatureComponents = $this->helper->generateSignature(
            'app_rigister_confirmation',
            $user->getId(),
            $user->getEmail()
        );

        $email = (new TemplatedEmail())
            ->to(new Address($user->getEmail(), $user->getName()))
            ->htmlTemplate('emails/register-confirmation.html.twig')
            ->subject('Подтвердите регистрацию')
            ->context(['signedUrl' => $signatureComponents->getSignedUrl()]);

        $this->mailer->send($email);
    }
}