<?php

namespace App\DataFixtures;

use App\Entity\Social;
use Doctrine\Persistence\ObjectManager;

class SocialIconsFixtures extends BaseFixtures
{
    public function load(ObjectManager $manager)
    {

        $this->makeOne(Social::class, function ($social) use ($manager) {

            $this->setFields($social, 'facebook', 'facebook', 'https://facebook.com');

            $manager->persist($social);

        });

        $this->makeOne(Social::class, function ($social) use ($manager) {

            $this->setFields($social, 'Вконтакте', 'vk', 'https://vk.com');

            $manager->persist($social);

        });

        $this->makeOne(Social::class, function ($social) use ($manager) {

            $this->setFields($social, 'Инстаграм', 'instagram', 'https://instagram.com');

            $manager->persist($social);

        });

        $manager->flush();
    }

    protected function setFields(Social $social, string $name, string $code, string $link)
    {
        $social->setName($name);

        $social->setCode($code);

        $social->setLink($link);
    }
}
