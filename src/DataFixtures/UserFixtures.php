<?php

namespace App\DataFixtures;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends BaseFixtures
{
    protected $users = [
        [
            'email' => 'admin@mail.ru',
            'name' => 'Admin',
            'password' => '123456',
            'verified' => true,
            'roles' => ['ROLE_ADMIN']
        ],
        [
            'email' => 'john@mail.ru',
            'name' => 'John Johnson',
            'password' => '123456',
            'verified' => true,
            'roles' => []
        ],
        [
            'email' => 'bill@mail.ru',
            'name' => 'William Frederick',
            'password' => '123456',
            'verified' => true,
            'roles' => []
        ],
        [
            'email' => 'elis@mail.ru',
            'name' => 'Elis McFarlane',
            'password' => '123456',
            'verified' => false,
            'roles' => []
        ]
    ];

    /**
     * @var UserPasswordHasherInterface
     */
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load($manager)
    {
        foreach ($this->users as $key => $fields) {

            $this->makeOne(User::class, $this->saveUser($manager, $key, $fields));
        }

        $manager->flush();
    }

    protected function saveUser($manager, $key, $fields)
    {
        return function (User $user) use ($manager, $key, $fields) {

            $user
                ->setEmail($fields['email'])
                ->setName($fields['name'])
                ->setPassword($this->passwordHasher->hashPassword($user, $fields['password']))
                ->setIsVerified($fields['verified']);

            if ($fields['roles']) {
                $user->setRoles($fields['roles']);
            }

            $manager->persist($user);

            $this->addReference(get_class($user) . "|$key", $user);

        };
    }
}
