<?php

namespace App\DataFixtures;

use App\Entity\Theme;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;


class ThemeFixtures extends BaseFixtures implements DependentFixtureInterface
{
    protected $titles = [
        'Математика',
        'Физика',
        'Химия',
        'Экономика',
        'Литература'
    ];

    public function load($manager)
    {
        $this->manager = $manager;

        foreach ($this->titles as $key => $title) {

            $this->makeOne(Theme::class, function (Theme $theme) use ($manager, $key, $title) {

                /**
                 * @param Theme $theme
                 */

                $theme->setName($title);

                $manager->persist($theme);

                $this->addReference(get_class($theme) . "|$key", $theme);

            });
        }

        $manager->flush();

    }

    public function getDependencies()
    {
        return [
            UserFixtures::class
        ];
    }
}
