<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Devices;
use App\Entity\Exercises;
use App\Entity\Users;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixture extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
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

        $anotherUser = new Users();
        $anotherUser->setFirstName('first name another user');
        $anotherUser->setLastName('last name another user');
        $anotherUser->setLogin('another_user_test@example.com');
        $anotherUser->setEmail('another_user_test@example.com');

        $anotherUser->setPassword($this->passwordHasher->hashPassword($anotherUser, '$3CR3T'));

        $manager->persist($anotherUser);

        $firstDevice = new Devices();
        $firstDevice->setName('First Device')
            ->setSeoLink('first_device')
            ->setPreviewPicturePath('')
            ->setCreator($user)
            ->setCreated(new DateTime('now'));

        $manager->persist($firstDevice);

        $secondDevice = new Devices();
        $secondDevice->setName('Second Device')
            ->setSeoLink('second_device')
            ->setPreviewPicturePath('')
            ->setCreator($user)
            ->setCreated(new DateTime('now'));

        $manager->persist($secondDevice);

        $thirdDevice = new Devices();
        $thirdDevice->setName('Third Device')
            ->setSeoLink('third_device')
            ->setPreviewPicturePath('')
            ->setCreator($anotherUser)
            ->setCreated(new DateTime('now'));

        $manager->persist($thirdDevice);

        $anotherDevice = new Devices();
        $anotherDevice->setName('Another Device')
            ->setSeoLink('another_device')
            ->setPreviewPicturePath('')
            ->setCreator($user)
            ->setCreated(new DateTime('now'));

        $manager->persist($anotherDevice);

        $firstExercise = new Exercises();
        $firstExercise->setName('First Exercise')
            ->setSeoLink('first_exercise')
            ->setPreviewPicturePath('')
            ->setDescription('')
            ->setSpecialFeatures('')
            ->setCreator($user)
            ->setCreated(new DateTime('now'));

        $manager->persist($firstExercise);

        $secondExercise = new Exercises();
        $secondExercise->setName('Second Exercise')
            ->setSeoLink('second_exercise')
            ->setPreviewPicturePath('')
            ->setDescription('')
            ->setSpecialFeatures('')
            ->setCreator($user)
            ->setCreated(new DateTime('now'));

        $manager->persist($secondExercise);

        $thirdExercise = new Exercises();
        $thirdExercise->setName('Third Exercise')
            ->setSeoLink('third_exercise')
            ->setPreviewPicturePath('')
            ->setDescription('')
            ->setSpecialFeatures('')
            ->setCreator($anotherUser)
            ->setCreated(new DateTime('now'));

        $manager->persist($thirdExercise);

        $anotherExercise = new Exercises();
        $anotherExercise->setName('Another Exercise')
            ->setSeoLink('another_exercise')
            ->setPreviewPicturePath('')
            ->setDescription('')
            ->setSpecialFeatures('')
            ->setCreator($user)
            ->setCreated(new DateTime('now'));

        $manager->persist($anotherExercise);
        $manager->flush();
    }
}
