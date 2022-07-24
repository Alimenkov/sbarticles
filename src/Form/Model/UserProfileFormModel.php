<?php


namespace App\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\UniqueEmail;

class UserProfileFormModel
{
    /**
     * @Assert\NotBlank(message = "Заполните имя")
     */
    public $name;

    /**
     * @Assert\Email(message = "Email '{{ value }}' некорректен.")
     * @Assert\NotBlank (message="Заполните Email")
     * @UniqueEmail(message="Email уже существует в системе")
     */
    public $email;

    /**
     * @Assert\Length  (min="6", minMessage="Поле должно быть больше 6 символов")
     */
    public $plainPassword;

    public $confirmPassword;
}