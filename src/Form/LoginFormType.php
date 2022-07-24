<?php

namespace App\Form;

use App\Form\Model\UserLoginFormModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LoginFormType extends AbstractType
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
            ->add('password', PasswordType::class, [
                'label' => 'Пароль',
                'attr' => ['autocomplete' => 'new-password', 'placeholder' => 'Пароль'],
            ])
            ->add('_remember_me', CheckboxType::class, [
                'required' => false,
                'label' => 'Запомнить меня',
                'label_attr' => [
                    'class' => 'checkbox-custom'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserLoginFormModel::class,
            'csrf_protection' => false,
            'csrf_field_name' => '_csrf_token',
            'csrf_token_id' => 'authenticate',
        ]);
    }
}
