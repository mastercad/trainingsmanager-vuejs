<?php

declare(strict_types=1);

namespace App\Listener;

use App\Entity\Users;
use App\Service\FileUploader;
use DateTime;
use DirectoryIterator;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
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
//            'onFlush',
            'prePersist',
            'preUpdate',
            'postUpdate',
            'postPersist'
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
      if (method_exists($args->getObject(), 'getCreator') 
        && null === $args->getObject()->getCreator()
      ) {
        if ($args->getObject() instanceof Users) {
            $args->getObject()->setId(Uuid::uuid4());
        }
//        $args->getObject()->setCreator($this->loadUserByToken($this->tokenStorage->getToken()->getUser()));
        $args->getObject()->setCreator($this->tokenStorage->getToken()->getUser());
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
      if (method_exists($args->getObject(), 'setUpdater')
        && method_exists($args->getObject(), 'setUpdated')
      ) {
//          $user = $this->loadUserByToken($this->tokenStorage->getToken()->getUser());
          $user = $this->tokenStorage->getToken()->getUser();
          $exercise = $args->getObject();
          $exercise->setUpdater($user);
          $exercise->setUpdated(new DateTime('now'));
          if (preg_match('/\/uploads\/.*?\/(.*?\..*)$/', $exercise->getPreviewPicturePath(), $matches)) {
              $exercise->setPreviewPicturePath($matches[1]);
          }
      }
    }

    public function postPersist(LifecycleEventArgs $args)
    {
      $this->moveUploadedImagesToExerciseFolder($args);
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
      $this->moveUploadedImagesToExerciseFolder($args);
    }

    private function moveUploadedImagesToExerciseFolder(LifecycleEventArgs $args)
    {
//      $user = $this->loadUserByToken($this->tokenStorage->getToken()->getUser());
      $user = $this->tokenStorage->getToken()->getUser();
      $exercise = $args->getObject();
      $uploadDir = __DIR__.'/../../public/uploads/'.$user->getUserIdentifier();
      $imageDir = __DIR__.'/../../public/images/content/dynamic/exercises/'.$exercise->getId();

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
}
