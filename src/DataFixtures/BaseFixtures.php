<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

abstract class BaseFixtures extends Fixture
{
    abstract public function load(ObjectManager $manager);

    public function makeOne(string $class, callable $factory)
    {
        $entity = new $class();

        $factory($entity);
    }

    protected function getIndexReference($className, $key)
    {
        $reference = $this->getReference($className . "|$key");

        if (empty($reference)) {
            throw new \Exception('Не найдены ссылки на класс: ' . $className);
        }

      /*  if (empty($references[$key])) {
            throw new \Exception('Не найден объект по ключу: ' . $key);
        }*/

        return $reference;
    }
}
