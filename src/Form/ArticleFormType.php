<?php

namespace App\Form;

use App\Entity\Theme;
use App\Form\Model\ArticleFormModel;
use App\Repository\ThemeRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleFormType extends AbstractType
{
    private ThemeRepository $themeRepository;

    public function __construct(ThemeRepository $themeRepository)
    {
        $this->themeRepository = $themeRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('theme', EntityType::class, [
                'label' => 'Тематика',
                'required' => false,
                'class' => Theme::class,
                'choices' => $this->themeRepository->findAll(),
                'choice_label' => 'name',
                'placeholder' => '-'
            ])
            ->add('title', TextType::class, [
                'label' => 'Заголовок статьи',
                'required' => false
            ])
            ->add('keyword', TextType::class, [
                'label' => 'Ключевое слово',
                'required' => false
            ])
            ->add('keyword_1', TextType::class, [
                'label' => 'Родительный падеж',
                'required' => false
            ])
            ->add('keyword_2', TextType::class, [
                'label' => 'Дательный падеж',
                'required' => false
            ])
            ->add('keyword_3', TextType::class, [
                'label' => 'Винительный падеж',
                'required' => false
            ])
            ->add('keyword_4', TextType::class, [
                'label' => 'Творительный падеж',
                'required' => false
            ])
            ->add('keyword_5', TextType::class, [
                'label' => 'Предложный падеж',
                'required' => false
            ])
            ->add('keyword_6', TextType::class, [
                'label' => 'Множественное число',
                'required' => false
            ])
            ->add('sizeFrom', NumberType::class, [
                'label' => 'Размер статьи от',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Размер статьи от'
                ]
            ])
            ->add('sizeTo', NumberType::class, [
                'label' => 'до',
                'required' => false,
                'attr' => [
                    'placeholder' => 'до'
                ]
            ])
            ->add('promotedWords', CollectionType::class, [
                'label' => 'Продвигаемые слова',
                'required' => false,
                'entry_type' => PromotedWordType::class,
                'entry_options' => ['label' => 'Продвигаемые слова'],
                'allow_add' => true,
            ])
            ->add('images', CollectionType::class, [
                'label' => 'Изображения',
                'required' => false,
                'entry_type' => ArticleImageType::class,
                'entry_options' => ['label' => 'Изображения'],
                'allow_add' => true,
            ])

        ;


    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ArticleFormModel::class,
        ]);
    }
}
