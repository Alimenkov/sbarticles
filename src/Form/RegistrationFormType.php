<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('email', EmailType::class, [
                'label' => 'E-mail',
                'attr' => [
                    'placeholder' => 'E-mail'
                ]
            ])
            ->add('name', TextType::class, [
                'label' => 'Имя',
                'attr' => [
                    'placeholder' => 'Имя'
                ]
            ])
            ->add('plainPassword', PasswordType::class, [
                'mapped' => false,
                'label' => 'Пароль',
                'attr' => ['autocomplete' => 'new-password', 'placeholder' => 'Пароль'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Введите пароль пожалуйста',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Ваш пароль должен быть более {{ limit }} символов',
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('confirmPassword', PasswordType::class, [
                'mapped' => false,
                'label' => 'Подтверждение пароля',
                'attr' => ['autocomplete' => 'new-password', 'placeholder' => 'Подтверждение пароля'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Введите подтверждение пароля пожалуйста',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Ваш пароль должен быть более {{ limit }} символов',
                        'max' => 4096,
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
