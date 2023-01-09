<?php

namespace App\DataFixtures;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use App\Entity\User;
use App\Entity\Bank;
use App\Entity\Client;
use App\Entity\Agency;
use App\Entity\Manager;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $managerObject): void
    {

        //CREATE A DEFAULT USER AS ROLE_SUPER_ADMIN
        $userAdmin = new User();
        $userAdmin->setName('admin');
        $userAdmin->setEmail('admin@email.com');
        $userAdmin->setPassword($this->hasher->hashPassword($userAdmin, '123456'));
        $userAdmin->setRoles(['ROLE_SUPER_ADMIN']);

        //CREATE A DEFAULT USER AS ROLE_ADMIN
        $userManager = new User();
        $userManager->setName('manager');
        $userManager->setEmail('manager@email.com');
        $userManager->setPassword($this->hasher->hashPassword($userManager, '123456'));
        $userManager->setRoles(['ROLE_ADMIN']);

        //CREATE A DEFAULT USER and CLIENT AS ROLE_USER
        $user = new User();
        $user->setName('user');
        $user->setEmail('user@email.com');
        $user->setPassword($this->hasher->hashPassword($user, '123456'));
        $user->setRoles(['ROLE_USER']);
        $client = new Client();
        $client->setAddress('default');
        $client->setPhone(0);
        $client->setActive(0);
        $client->setUser($user);
        $user->setClient($client);

        //CREATE A DEFAULT BANK
        $bank = new Bank();
        $bank->setName('Default Bank');

        //CREATE A DEFAULT AGENCY
        $agency = new Agency();
        $agency->setNumber(1234);
        $agency->setAddress('Rua João Paulo XII');
        $agency->setBank($bank);

        //CREATE A DEFAULT MANAGER
        $manager = new Manager();
        $manager->setUser($userManager);
        $manager->setAgency($agency);

        $managerObject->persist($userAdmin);
        $managerObject->persist($userManager);
        $managerObject->persist($user);
        $managerObject->persist($bank);
        $managerObject->persist($agency);
        $managerObject->persist($manager);

        $managerObject->flush();
    }
}
