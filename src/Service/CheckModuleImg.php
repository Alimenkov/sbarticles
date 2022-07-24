<?php

namespace App\Service;

class CheckModuleImg
{
    public function check(string $text): bool
    {
        preg_match('/{{\s*image(?:S|s)rc\s*}}/', $text, $match);

        return !empty($match);
    }
}