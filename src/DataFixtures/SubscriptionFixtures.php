<?php

namespace App\DataFixtures;

use App\Entity\Subscription;


class SubscriptionFixtures extends BaseFixtures
{
    protected $subscriptions = [
        [
            'name' => 'Free',
            'price' => 0,
            'much' => true,
            'basic' => true,
            'pro' => false,
            'modules' => false,
        ],
        [
            'name' => 'Plus',
            'price' => 9,
            'much' => true,
            'basic' => true,
            'pro' => true,
            'modules' => false,
        ],
        [
            'name' => 'Pro',
            'price' => 49,
            'much' => true,
            'basic' => true,
            'pro' => true,
            'modules' => true,
        ],
    ];

    public function load($manager)
    {
        $this->manager = $manager;

        foreach ($this->subscriptions as $fields) {

            $this->makeOne(Subscription::class, function (Subscription $subscription) use ($manager, $fields) {

                $subscription
                    ->setName($fields['name'])
                    ->setPrice($fields['price'])
                    ->setMuch($fields['much'])
                    ->setBasic($fields['basic'])
                    ->setPro($fields['pro'])
                    ->setModules($fields['modules']);

                $manager->persist($subscription);

            });
        }

        $manager->flush();

    }
}
