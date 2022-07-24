<?php


namespace App\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;

class ArticleFormModel
{

    public $theme;

    public $title;

    /**
     * @Assert\NotBlank(message = "Ключевое слово нужно указать")
     */
    public $keyword;

    public $keyword_1;

    public $keyword_2;

    public $keyword_3;

    public $keyword_4;

    public $keyword_5;

    public $keyword_6;

    public $sizeFrom;

    public $sizeTo;

    public $promotedWords;

    public $images;

}