<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"PROPERTY", "ANNOTATION"})
 */
class UniqueEmail extends Constraint
{
    public $message = 'Пользователь "{{ value }}" существует.';
}
