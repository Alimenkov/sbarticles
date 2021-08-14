<?php


namespace App\Service;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class ChangeEmail
{
    private VerifyEmailHelperInterface $helper;
    private MailerInterface $mailer;
    private ?UserInterface $user;
    private UrlGeneratorInterface $urlGenerator;
    private RequestStack $requestStack;
    private EntityManagerInterface $em;

    public function __construct(
        VerifyEmailHelperInterface $emailHelper,
        MailerInterface            $mailer,
        Security                   $security,
        UrlGeneratorInterface      $urlGenerator,
        EntityManagerInterface     $em,
        RequestStack               $requestStack
    )
    {
        $this->helper = $emailHelper;
        $this->mailer = $mailer;
        $this->user = $security->getUser();
        $this->urlGenerator = $urlGenerator;
        $this->requestStack = $requestStack;
        $this->em = $em;
    }

    public function send(string $newEmail)
    {
        if (!empty($this->user)) {

            $email = (new TemplatedEmail())
                ->to(new Address($this->user->getEmail(), $this->user->getName()))
                ->htmlTemplate('emails/change-profile-email.html.twig')
                ->subject('Подтвердите смену email')
                ->context(['signedUrl' => $this->formatUrl($newEmail)]);

            $this->mailer->send($email);

            $this->addMessage('template_message', 'На новый Email выслано письмо подтверждения');
        }
    }

    protected function formatUrl(string $newEmail): string
    {
        $signatureComponents = $this->helper->generateSignature(
            'app_change_email_confirmation',
            $this->user->getId(),
            $newEmail
        );

        $params = $this->parseUrl($signatureComponents->getSignedUrl());

        $params['email'] = $newEmail;

        return $this->getUrl($params);

    }

    protected function normalizeUrl(string $url): string
    {
        $params = $this->parseUrl($url);

        if (isset($params['email'])) {
            unset($params['email']);
        }

        return $this->getUrl($params);
    }

    protected function getUrl(array $params): string
    {
        return $this->urlGenerator->generate('app_change_email_confirmation', $params, UrlGeneratorInterface::ABSOLUTE_URL);
    }

    protected function parseUrl(string $url)
    {
        $urlComponents = parse_url($url);

        parse_str($urlComponents['query'], $params);

        return $params;
    }

    public function tryToChangeEmail(Request $request): void
    {
        $email = $this->getEmail($request);

        try {
            $this->helper->validateEmailConfirmation($this->normalizeUrl($request->getUri()), $this->user->getId(), $email);
        } catch (VerifyEmailExceptionInterface $e) {
            $this->addMessage('verify_email_error', 'Ошибка подтверждения нового email');
        }

        $this->user->setEmail($email);

        $this->em->persist($this->user);

        $this->em->flush();

        $this->addMessage('template_message', 'Email успешно изменён');

    }

    protected function addMessage(string $type, string $text)
    {
        $this->requestStack
            ->getSession()
            ->getFlashBag()
            ->add($type, $text);
    }

    protected function getEmail(Request $request): ?string
    {
        return $request->query->get('email');
    }

}