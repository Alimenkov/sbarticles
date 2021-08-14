<?php

namespace App\Form;

use App\Form\Model\UserProfile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfileFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        parent::buildForm($builder, $options);

        $builder
            ->add('name', TextType::class, [
                'label' => 'Имя',
                'attr' => [
                    'placeholder' => 'Имя'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'E-mail',
                'attr' => [
                    'placeholder' => 'E-mail'
                ]
            ])
            ->add('plainPassword', PasswordType::class, [
                'required' => false,
                'label' => 'Пароль',
                'attr' => ['autocomplete' => 'new-password']
            ])
            ->add('confirmPassword', PasswordType::class, [
                'required' => false,
                'label' => 'Подтверждение пароля',
                'attr' => ['autocomplete' => 'new-password'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserProfile::class,
        ]);
    }
}
