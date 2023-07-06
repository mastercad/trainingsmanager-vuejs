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
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Lexik\Bundle\JWTAuthenticationBundle\Security\User\JWTUser;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

use function is_dir;
use function is_object;
use function method_exists;
use function mkdir;
use function preg_match;
use function preg_replace;
use function strpos;
use function strtolower;

class ExerciseListener implements EventSubscriberInterface
{
    /**
     * CTOR of exerciseListener class.
     */
    public function __construct(private TokenStorageInterface $tokenStorage, private EntityManagerInterface $entityManager, private FileUploader $fileUploader)
    {
    }

    /**
     * Provides list of subscribed events.
     *
     * @return string[]
     */
    public function getSubscribedEvents(): array
    {
        return [
            Events::prePersist,
            Events::preUpdate,
            Events::postPersist,
            Events::postUpdate,
        ];
    }

    /**
     * Function for prePersist event.
     */
    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if (
            $entity instanceof Users
            || ! method_exists($entity, 'getCreator')
            || $entity->getCreator() !== null
        ) {
            return;
        }

        if ($entity instanceof Users) {
            $entity->setId(Uuid::uuid4());
        }

        $this->handleSeoLinkForCreate($entity);

        $user = $this->tokenStorage->getToken() !== null && $this->tokenStorage->getToken()->getUser() instanceof Users ?
        $this->tokenStorage->getToken()->getUser() :
        $this->loadUserByToken($this->tokenStorage->getToken()->getUser());

        $entity->setCreator($user);
        $entity->setCreated(new DateTime('now'));

        if (
            ! ($entity instanceof Exercises)
            && ! ($entity instanceof Devices)
            || ! preg_match('/\/uploads\/.*?\/(.*?\..*)$/', $entity->getPreviewPicturePath(), $matches)
        ) {
            return;
        }

        $entity->setPreviewPicturePath($matches[1]);
    }

    /**
     * Function for preUpdate event.
     */
    public function preUpdate(PreUpdateEventArgs $args): void
    {
        $entity = $args->getObject();

        if (
            $entity instanceof Users
            || ! method_exists($entity, 'setUpdater')
            || ! method_exists($entity, 'setUpdated')
        ) {
            return;
        }

        $user = $this->tokenStorage->getToken()->getUser() instanceof Users ?
        $this->tokenStorage->getToken()->getUser() :
        $this->loadUserByToken($this->tokenStorage->getToken()->getUser());
        $entity->setUpdater($user);
        $entity->setUpdated(new DateTime('now'));

        $this->handleSeoLinkForUpdate($entity);

        $user = $this->tokenStorage->getToken() !== null && $this->tokenStorage->getToken()->getUser() instanceof Users ?
        $this->tokenStorage->getToken()->getUser() :
        $this->loadUserByToken($this->tokenStorage->getToken()->getUser());

        $entity->setUpdater($user);
        $entity->setUpdated(new DateTime('now'));

        if (
            ! ($entity instanceof Exercises)
            && ! ($entity instanceof Devices)
            || ! preg_match('/\/uploads\/.*?\/(.*?\..*)$/', $entity->getPreviewPicturePath(), $matches)
        ) {
            return;
        }

        $entity->setPreviewPicturePath($matches[1]);
    }

    public function postPersist(PostPersistEventArgs $args): void
    {
        $this->moveUploadedImagesToExerciseFolder($args);
    }

    public function postUpdate(PostUpdateEventArgs $args): void
    {
        $this->moveUploadedImagesToExerciseFolder($args);
    }

    private function moveUploadedImagesToExerciseFolder(LifecycleEventArgs $args): void
    {
        $object = $args->getObject();

        if (
            ! $object instanceof Exercises
            && ! $object instanceof Devices
        ) {
            return;
        }

        $user = $this->tokenStorage->getToken()->getUser() instanceof Users ?
        $this->tokenStorage->getToken()->getUser() :
        $this->loadUserByToken($this->tokenStorage->getToken()->getUser());

        $targetPathPart = '';
        if (0 < strpos($object::class, 'Exercises')) {
            $targetPathPart = 'exercises';
        } elseif (0 < strpos($object::class, 'Devices')) {
            $targetPathPart = 'devices';
        }

        $uploadDir = __DIR__ . '/../../public/uploads/' . $user->getUserIdentifier();
        $imageDir = __DIR__ . '/../../public/images/content/dynamic/' . $targetPathPart . '/' . $object->getId();

        if (! is_dir($imageDir)) {
            mkdir($imageDir, 0777, true);
        }

        $directoryIterator = new DirectoryIterator($uploadDir);
        foreach ($directoryIterator as $uploadedFile) {
            if (! $uploadedFile->isFile()) {
                continue;
            }

            $file = new File($uploadedFile->getPathname());
            $file->move($imageDir, $file->getFilename());
        }
    }

    /**
     * Load user by given jwt token user.
     */
    private function loadUserByToken(JWTUser $jWTUser): Users
    {
        return $this->entityManager->getRepository(Users::class)->findOneBy(
            ['email' => $jWTUser->getUserIdentifier()],
        );
    }

    private function handleSeoLinkForCreate(mixed $entity): mixed
    {
        if (
            ! is_object($entity)
            || ! method_exists($entity, 'getSeoLink')
            || ! method_exists($entity, 'setSeoLink')
        ) {
            return null;
        }

        if (empty($entity->getSeoLink())) {
            $entity->setSeoLink($this->convertToSnakeCase($entity->getName()));
        }

        $repository = $this->entityManager->getRepository($entity::class);

        $existingSeoLink = $repository->findOneBy(['seoLink' => $entity->getSeoLink()]);
        if ($existingSeoLink) {
            $entity->setSeoLink($this->incrementSeoLink($entity->getSeoLink()));

            return $this->handleSeoLinkForCreate($entity);
        }

        return $entity;
    }

    private function handleSeoLinkForUpdate(mixed $entity): mixed
    {
        if (
            ! is_object($entity)
            || ! method_exists($entity, 'getSeoLink')
            || ! method_exists($entity, 'setSeoLink')
        ) {
            return null;
        }

        $repository = $this->entityManager->getRepository($entity::class);

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

    private function convertToSnakeCase(string $name): string
    {
        return preg_replace('/[^\p{L}0-9\s]+/u', '_', strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $name)));
    }

    private function incrementSeoLink(string $seoLink): string
    {
        if (preg_match('/(.*?)_([0-9]+)$/', $seoLink, $matches)) {
            return $matches[1] . '_' . (++$matches[2]);
        }

        return $seoLink . '_1';
    }
}
