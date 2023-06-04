<?php

declare(strict_types=1);

namespace App\Listener;

use App\Entity\Devices;
use App\Entity\Exercises;
use App\Entity\Users;
use App\Service\FileUploader;
use DateTime;
use DirectoryIterator;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Event\PostRemoveEventArgs;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Lexik\Bundle\JWTAuthenticationBundle\Security\User\JWTUser;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ExerciseListener implements EventSubscriberInterface
{
    private TokenStorageInterface $tokenStorage;
    private EntityManagerInterface $entityManager;
    private FileUploader $fileUploader;

    /**
     * Provides list of subscribed events.
     *
     * @return void
     */
    public function getSubscribedEvents()
    {
        return array(
          Events::prePersist,
          Events::preUpdate,
          Events::postPersist,
          Events::postUpdate
        );
    }

    /**
     * CTOR of exerciseListener class.
     *
     * @param TokenStorageInterface $tokenStorage
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(TokenStorageInterface $tokenStorage, EntityManagerInterface $entityManager, FileUploader $fileUploader)
    {
        $this->tokenStorage = $tokenStorage;
        $this->entityManager = $entityManager;
        $this->fileUploader = $fileUploader;
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
      $entity = $args->getObject();

      if (!$entity instanceof Users
        && method_exists($entity, 'getCreator')
        && null === $entity->getCreator()
      ) {
        if ($entity instanceof Users) {
            $entity->setId(Uuid::uuid4());
        }

        $this->handleSeoLinkForCreate($entity);

        $user = null !== $this->tokenStorage->getToken() && $this->tokenStorage->getToken()->getUser() instanceof Users ?
          $this->tokenStorage->getToken()->getUser() :
          $this->loadUserByToken($this->tokenStorage->getToken()->getUser());

        $entity->setCreator($user);
//        $args->getObject()->setCreator($this->tokenStorage->getToken()->getUser());
        $entity->setCreated(new DateTime('now'));

        if (
          (
            $entity instanceof Exercises
            || $entity instanceof Devices
          )
          && preg_match('/\/uploads\/.*?\/(.*?\..*)$/', $entity->getPreviewPicturePath(), $matches)
        ) {
            $entity->setPreviewPicturePath($matches[1]);
        }
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
      $entity = $args->getObject();

      if (!$entity instanceof Users
        && method_exists($entity, 'setUpdater')
        && method_exists($entity, 'setUpdated')
      ) {
          $user = $this->tokenStorage->getToken()->getUser() instanceof Users ?
            $this->tokenStorage->getToken()->getUser() :
            $this->loadUserByToken($this->tokenStorage->getToken()->getUser());
//          $user = $this->tokenStorage->getToken()->getUser();
          $entity->setUpdater($user);
          $entity->setUpdated(new DateTime('now'));

          $this->handleSeoLinkForUpdate($entity);

          $user = null !== $this->tokenStorage->getToken() && $this->tokenStorage->getToken()->getUser() instanceof Users ?
            $this->tokenStorage->getToken()->getUser() :
            $this->loadUserByToken($this->tokenStorage->getToken()->getUser());

          $entity->setUpdater($user);
  //        $args->getObject()->setCreator($this->tokenStorage->getToken()->getUser());
          $entity->setUpdated(new DateTime('now'));

        if (
          (
            $entity instanceof Exercises
            || $entity instanceof Devices
          )
          && preg_match('/\/uploads\/.*?\/(.*?\..*)$/', $entity->getPreviewPicturePath(), $matches)
        ) {
            $entity->setPreviewPicturePath($matches[1]);
        }
      }
    }

    public function postPersist(PostPersistEventArgs $args)
    {
      $this->moveUploadedImagesToExerciseFolder($args);
    }

    public function postUpdate(PostUpdateEventArgs $args)
    {
      $this->moveUploadedImagesToExerciseFolder($args);
    }

    private function moveUploadedImagesToExerciseFolder(LifecycleEventArgs $args)
    {
      $object = $args->getObject();

      if (!$object instanceof Exercises
        && !$object instanceof Devices
      ) {
        return;
      }

      $user = $this->tokenStorage->getToken()->getUser() instanceof Users ?
        $this->tokenStorage->getToken()->getUser() :
        $this->loadUserByToken($this->tokenStorage->getToken()->getUser());

      $targetPathPart = '';
      if (0 < strpos(get_class($object), 'Exercises')) {
        $targetPathPart = 'exercises';
      } else if (0 < strpos(get_class($object), 'Devices')) {
        $targetPathPart = 'devices';
      }

        $uploadDir = __DIR__.'/../../public/uploads/'.$user->getUserIdentifier();
        $imageDir = __DIR__.'/../../public/images/content/dynamic/'.$targetPathPart.'/'.$object->getId();

        if (!is_dir($imageDir)) {
            mkdir($imageDir, 0777, true);
        }

        $directoryIterator = new DirectoryIterator($uploadDir);
        foreach ($directoryIterator as $uploadedFile) {
            if ($uploadedFile->isFile()) {
                $file = new File($uploadedFile->getPathname());
                $file->move($imageDir, $file->getFilename());
            }
        }
    }

    /**
     * Load user by given jwt token user.
     *
     * @param JWTUser $jWTUser
     *
     * @return Users
     */
    private function loadUserByToken(JWTUser $jWTUser)
    {
      return $this->entityManager->getRepository(Users::class)->findOneBy(
        ['email' => $jWTUser->getUserIdentifier()]
      );
    }

    private function handleSeoLinkForCreate($entity)
    {
      if (!is_object($entity)
        || !method_exists($entity, 'getSeoLink')
        || !method_exists($entity, 'setSeoLink')
      ) {
        return null;
      }

      if (empty($entity->getSeoLink())) {
        $entity->setSeoLink($this->convertToSnakeCase($entity->getName()));
      }

      $repository = $this->entityManager->getRepository(get_class($entity));

      $existingSeoLink = $repository->findOneBy(['seoLink' => $entity->getSeoLink()]);
      if ($existingSeoLink) {
        $entity->setSeoLink($this->incrementSeoLink($entity->getSeoLink()));

        return $this->handleSeoLinkForCreate($entity);
      }

      return $entity;
    }

    private function handleSeoLinkForUpdate($entity)
    {
      if (!is_object($entity)
        || !method_exists($entity, 'getSeoLink')
        || !method_exists($entity, 'setSeoLink')
      ) {
        return null;
      }

      $repository = $this->entityManager->getRepository(get_class($entity));

      $newSeoLink = $this->convertToSnakeCase($entity->getName());
      if ($newSeoLink !== $entity->getSeoLink()) {
        $existingSeoLink = $repository->findOneBy(['seoLink' => $entity->getSeoLink()]);
        if ($existingSeoLink) {
          $entity->setSeoLink($this->incrementSeoLink($entity->getSeoLink()));

          return $this->handleSeoLinkForCreate($entity);
        }
      }

      return $entity;
    }

    private function convertToSnakeCase(?string $name)
    {
      return preg_replace('/[^\p{L}0-9\s]+/u', '_', strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $name)));
    }

    private function incrementSeoLink(string $seoLink)
    {
      if (preg_match('/(.*?)_([0-9]+)$/', $seoLink, $matches)) {
        return $matches[1]."_".(++$matches[2]);
      }

      return $seoLink."_1";
    }
}
