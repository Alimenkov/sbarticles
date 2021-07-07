<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

abstract class BaseFixtures extends Fixture
{
    abstract public function load(ObjectManager $manager);

    protected function make(string $class, callable $function, int $count): void
    {
        for ($n = 0; $n < $count; $n++) {

            $entity = new $class();

            $function($entity);
        }
    }
}
