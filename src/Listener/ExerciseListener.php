<?php

declare(strict_types=1);

namespace App\Listener;

use App\Entity\Users;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Lexik\Bundle\JWTAuthenticationBundle\Security\User\JWTUser;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ExerciseListener implements EventSubscriberInterface
{
    private TokenStorageInterface $tokenStorage;
    private EntityManagerInterface $entityManager;

    /**
     * Provides list of subscribed events.
     *
     * @return void
     */
    public function getSubscribedEvents()
    {
        return array(
//            'onFlush',
            'prePersist',
            'preUpdate'
        );
    }

    /**
     * CTOR of exerciseListener class.
     *
     * @param TokenStorageInterface $tokenStorage
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(TokenStorageInterface $tokenStorage, EntityManagerInterface $entityManager)
    {
        $this->tokenStorage = $tokenStorage;
        $this->entityManager = $entityManager;
    }

    /**
     * Function for prePersist event.
     *
     * @param LifecycleEventArgs $args
     *
     * @return void
     */
    public function prePersist(LifecycleEventArgs $args)
    {
      if (null === $args->getObject()->getCreator()) {
        if ($args->getObject() instanceof Users) {
            $args->getObject()->setId(Uuid::uuid4());
        }
        $args->getObject()->setCreator($this->loadUserByToken($this->tokenStorage->getToken()->getUser()));
        $args->getObject()->setCreated(new DateTime('now'));
      }
    }

    /**
     * Function for preUpdate event.
     *
     * @param PreUpdateEventArgs $args
     *
     * @return void
     */
    public function preUpdate(PreUpdateEventArgs $args)
    {
      $args->getObject()->setUpdater($this->loadUserByToken($this->tokenStorage->getToken()->getUser()));
      $args->getObject()->setUpdated(new DateTime('now'));
    }

    /**
     * Load user by given jwt token user.
     *
     * @param JWTUser $jWTUser
     *
     * @return void
     */
    private function loadUserByToken(JWTUser $jWTUser)
    {
      return $this->entityManager->getRepository(Users::class)->findOneBy(
        ['email' => $jWTUser->getUserIdentifier()]
      );
    }
}
