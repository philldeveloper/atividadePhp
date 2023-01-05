<?php

namespace App\DataFixtures;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use App\Entity\User;
use App\Entity\Bank;
use App\Entity\Agency;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $managerObject): void
    {
        //CREATE A DEFAULT BANK
        $bank = new Bank();
        $bank->setName('Default Bank');

        //CREATE A DEFAULT AGENCY
        $agency = new Agency();
        $agency->setNumber(1234);
        $agency->setAddress('Rua João Paulo XII');
        $agency->setBank($bank);

        //ROLE_SUPER_ADMIN
        $admin = new User();
        $admin->setName('admin');
        $admin->setEmail('admin@email.com');
        $admin->setPassword($this->hasher->hashPassword($admin, '123456'));
        $admin->setRoles(['ROLE_SUPER_ADMIN']);

        //ROLE_ADMIN
        $manager = new User();
        $manager->setName('manager');
        $manager->setEmail('manager@email.com');
        $manager->setPassword($this->hasher->hashPassword($manager, '123456'));
        $manager->setRoles(['ROLE_ADMIN']);

        //ROLE_USER
        $user = new User();
        $user->setName('user');
        $user->setEmail('user@email.com');
        $user->setPassword($this->hasher->hashPassword($user, '123456'));
        $user->setRoles(['ROLE_USER']);

        $managerObject->persist($bank);
        $managerObject->persist($admin);
        $managerObject->persist($manager);
        $managerObject->persist($user);
        $managerObject->flush();
    }
}
