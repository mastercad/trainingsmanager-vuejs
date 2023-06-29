<?php

namespace App\DataFixtures;

use App\Entity\Users;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixture extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
      $this->passwordHasher = $passwordHasher;
    }
    public function load(ObjectManager $manager): void
    {
      $user = new Users();
      $user->setFirstName('first name');
      $user->setLastName('last name');
      $user->setLogin('test@example.com');
      $user->setEmail('test@example.com');

      $user->setPassword($this->passwordHasher->hashPassword($user, '$3CR3T'));

      $manager->persist($user);
      $manager->flush();
    }
}
