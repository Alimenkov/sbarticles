<?php


namespace App\Service;


use App\Entity\User;
use App\Form\Model\UserProfile;
use App\Form\ProfileFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class ChangeProfile
{
    private RequestStack $requestStack;
    private FormInterface $form;
    private ?UserInterface $user;
    private bool $changed = false;
    private UserPasswordHasherInterface $passwordHasher;
    private EntityManagerInterface $em;
    private ChangeEmail $changeEmail;

    public function __construct(
        Security $security,
        RequestStack $requestStack,
        FormFactoryInterface $formFactory,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $em,
        ChangeEmail $changeEmail
    )
    {
        $this->requestStack = $requestStack;

        $this->session = $requestStack->getSession();

        $this->user = $this->getUser($security);

        $this->form = $formFactory->create(ProfileFormType::class, $this->makeFormModel(), []);

        $this->passwordHasher = $passwordHasher;

        $this->em = $em;

        $this->changeEmail = $changeEmail;
    }

    protected function getUser(Security $security): UserInterface
    {
        $user = $security->getUser();

        if (empty($user)) {
            $user = new User();
        }

        return $user;
    }

    public function makeForm(): FormInterface
    {

        $this->form->handleRequest($this->requestStack->getCurrentRequest());

        if ($this->form->isSubmitted() && $this->form->isValid()) {

            $this->setEmailName();

            $this->setPassword();
        }

        return $this->form;
    }

    protected function setEmailName(): void
    {
        $email = $this->form->get('email')->getData();

        $name = $this->form->get('name')->getData();

        if (empty($email) || empty($name)) {

            $errorEmailName = new FormError('Имя и email должны быть заполнены');

            $this->form->addError($errorEmailName);

        } else {

            if (strcasecmp($this->user->getEmail(), $email) != 0) {
                $this->changeEmail($email);
            }

            if (strcasecmp($this->user->getName(), $name) != 0) {
                $this->user->setName($name);
                $this->setChanged();
            }
        }
    }

    protected function setPassword(): void
    {
        $plainPasswd = $this->form->get('plainPassword')->getData();

        $confirmPasswd = $this->form->get('confirmPassword')->getData();

        if (!empty($plainPasswd)) {

            if (strcasecmp($plainPasswd, $confirmPasswd) != 0) {

                $errorPasswd = new FormError('Пароли не совпадают');

                $this->form->addError($errorPasswd);

            } else {

                $this->user->setPassword(
                    $this->passwordHasher->hashPassword(
                        $this->user,
                        $plainPasswd
                    )
                );

                $this->setChanged();
            }
        }
    }

    protected function changeEmail(string $email): void
    {
        $this->changeEmail->send($email);
    }

    protected function makeFormModel(): UserProfile
    {
        $formModel = new UserProfile();

        $formModel->name = $this->user->getName();

        $formModel->email = $this->user->getEmail();

        return $formModel;
    }

    public function save(): bool
    {
        if ($this->isChanged()) {

            $this->em->persist($this->user);

            $this->em->flush();

            $this->requestStack
                ->getSession()
                ->getFlashBag()
                ->add('template_message', 'Профиль успешно изменён');

            return true;
        }

        return false;
    }

    protected function isChanged()
    {
        return !empty($this->changed);
    }

    protected function setChanged()
    {
        if (empty($this->changed)) {
            $this->changed = true;
        }
    }
}