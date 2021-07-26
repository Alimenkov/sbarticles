<?php

namespace App\DataFixtures;

use App\Entity\Module;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;


class ModuleFixtures extends BaseFixtures implements DependentFixtureInterface
{
    protected $modules = [
        [
            'name' => 'Название с текстом',
            'content' => '<h1>{{ title }}</h1>
<p>{{ paragraph }}</p>
            ',

        ],
        [
            'name' => 'Текст справа',
            'content' => '<p class="text-right">{{ paragraph }}</p>',
        ],
        [
            'name' => 'Текст слева',
            'content' => '<p class="text-left">{{ paragraph }}</p>',
        ],
        [
            'name' => 'Текст в две колонки',
            'content' => '<div class="row">
  <div class="col-sm-6">
    {{ paragraphs }}
  </div>
  <div class="col-sm-6">
    {{ paragraphs }}
  </div>
</div>',
        ],
        [
            'name' => 'Текст картинка слева',
            'content' => '<div class="row">
  <div class="col-sm-3">
    <img src="{{ imageSrc }} " alt=""/>
  </div>
  <div class="col-sm-9">
    {{ paragraphs }}
  </div>
</div>'
        ],
        [
            'name' => 'Текст картинка справа',
            'content' => '<div class="row">  
  <div class="col-sm-9">
    {{ paragraphs }}
  </div>
  <div class="col-sm-3">
    <img src="{{ imageSrc }} " alt=""/>
  </div>
</div>'
        ],
        [
            'name' => 'Текст с картинкой',
            'content' => '<div class="row">
<div class="col-sm-12">
    <img src="{{ imageSrc }} " alt=""/>
  </div>
  <div class="col-sm-12">
    {{ paragraphs }}
  </div>  
</div>'
        ],
        [
            'name' => 'Обычный текст',
            'content' => '<div class="row">
  <div class="col-sm-12">
    {{ paragraphs }}
  </div>  
</div>'
        ]
    ];

    public function load($manager)
    {
        $this->manager = $manager;

        foreach ($this->modules as $fields) {

            $this->makeOne(Module::class, function (Module $module) use ($manager, $fields) {

                $module
                    ->setName($fields['name'])
                    ->setContent($fields['content']);

                $manager->persist($module);

            });
        }

        $manager->flush();

    }

    public function getDependencies()
    {
        return [
            ThemeFixtures::class
        ];
    }
}
