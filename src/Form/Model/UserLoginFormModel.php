<?php


namespace App\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;

class UserLoginFormModel
{
    /**
     * @Assert\NotBlank (message="Заполните Email")
     * @Assert\Email(message="Email некорректен")
     */
    public $email;

    /**
     * @Assert\Length  (min="6", minMessage="Поле должно быть больше 6 символов")
     * @Assert\NotBlank (message="Укажите пароль")
     */
    public $password;

    public $_remember_me;
    
}