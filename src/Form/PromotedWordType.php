<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PromotedWordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('word', TextType::class, [
                'label' => 'Продвигаемое слово',
                'attr' => [
                    'placeholder' => 'кол-во'
                ]
            ])
            ->add('size', NumberType::class, [
                'label' => 'кол-во',
                'attr' => [
                    'placeholder' => 'кол-во'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PromotedWordType::class,
        ]);
    }
}
