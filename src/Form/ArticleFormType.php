<?php

namespace App\Form;

use App\Entity\Theme;
use App\Form\Model\ArticleFormModel;
use App\Repository\ThemeRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

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
                'class' => Theme::class,
                'choices' => $this->themeRepository->findAll(),
                'choice_label' => 'name',
                'placeholder' => '-'
            ])
            ->add('title', TextType::class, [
                'label' => 'Заголовок статьи',
            ])
            ->add('keyword', TextType::class, [
                'label' => 'Ключевое слово',
            ])
            ->add('keyword_1', TextType::class, [
                'label' => 'Родительный падеж',
            ])
            ->add('keyword_2', TextType::class, [
                'label' => 'Дательный падеж',
            ])
            ->add('keyword_3', TextType::class, [
                'label' => 'Винительный падеж',
            ])
            ->add('keyword_4', TextType::class, [
                'label' => 'Творительный падеж',
            ])
            ->add('keyword_5', TextType::class, [
                'label' => 'Предложный падеж',
            ])
            ->add('keyword_6', TextType::class, [
                'label' => 'Множественное число',
            ])
            ->add('sizeFrom', NumberType::class, [
                'label' => 'Размер статьи от',
                'attr' => [
                    'placeholder' => 'Размер статьи от'
                ]
            ])
            ->add('sizeTo', NumberType::class, [
                'label' => 'до',
                'attr' => [
                    'placeholder' => 'до'
                ]
            ])

            ->add('images', FileType::class, [
                'label' => 'Изображения',
                'constraints' => [
                    new Image(
                        [
                            'allowPortrait' => false,
                            'minWidth' => 480,
                            'minHeight' => 300,
                            'maxSize' => '2M',
                            'maxSizeMessage' => 'Максимальный размер файла 2Мб'
                        ]
                    )
                ]
            ])
            ->add('promotedWords', CollectionType::class, [
                'label' => 'Продвигаемые слова',
                'entry_type' => PromotedWordType::class,
                'entry_options' => ['label' => 'Продвигаемые слова'],
                'allow_add' => true,
            ]);


    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ArticleFormModel::class,
        ]);
    }
}
