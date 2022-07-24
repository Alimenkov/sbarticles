<?php

namespace App\Form;

use App\Form\Model\ArticleImageModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class ArticleImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('image', FileType::class, [
                'label' => 'Изображение',
                'required' => false,
                'constraints' => [
                    new Image(
                        [
                            'allowPortrait' => false,
                            'minWidth' => 480,
                            'minHeight' => 300,
                            'maxSize' => '10M',
                            'maxSizeMessage' => 'Максимальный размер файла 10Мб'
                        ]
                    )
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ArticleImageModel::class,
        ]);
    }
}
