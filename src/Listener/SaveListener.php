<?php

declare(strict_types=1);

namespace App\Listener;

use App\Entity\Devices;
use App\Entity\Exercises;
use App\Entity\Users;
use App\Service\FileUploader;
use App\Service\SeoLinkHandler;
use App\Service\UserProvider;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;

use function method_exists;
use function preg_match;
use function preg_quote;
use function strpos;

class SaveListener implements EventSubscriberInterface
{
    /**
     * CTOR of exerciseListener class.
     */
    public function __construct(
        private UserProvider $userProvider,
        private FileUploader $fileUploader,
        private SeoLinkHandler $seoLinkHandler
    ) {
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
    public function prePersist(PrePersistEventArgs $args): void
    {
        $entity = $args->getObject();

        if (
            $entity instanceof Users
            || ! method_exists($entity, 'getCreator')
            || $entity->getCreator() !== null
        ) {
            return;
        }

        $this->seoLinkHandler->handleSeoLinkForCreate($entity);

        $user = $this->userProvider->provide();

        $entity->setCreator($user);
        $entity->setCreated(new DateTime('now'));

        if (
            ! ($entity instanceof Exercises)
            && ! ($entity instanceof Devices)
            || ! preg_match('/\/uploads\/' . preg_quote($user->getEmail()) . '\/(.*?\..*)$/', $entity->getPreviewPicturePath(), $matches)
        ) {
            return;
        }

        $entity->setPreviewPicturePath($matches[1]);

        return;
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

        $user = $this->userProvider->provide();
        $entity->setUpdater($user);
        $entity->setUpdated(new DateTime('now'));

        $this->seoLinkHandler->handleSeoLinkForUpdate($entity);

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
        $this->moveUploadedImages($args);
    }

    public function postUpdate(PostUpdateEventArgs $args): void
    {
        $this->moveUploadedImages($args);
    }

    private function moveUploadedImages(LifecycleEventArgs $args): int
    {
        $object = $args->getObject();
        $user = $this->userProvider->provide();

        if (
            (! $object instanceof Exercises
            && ! $object instanceof Devices)
            || ! $user instanceof Users
        ) {
            return 0;
        }

        $targetPathPart = '';
        if (0 < strpos($object::class, 'Exercises')) {
            $targetPathPart = 'exercises';
        } elseif (0 < strpos($object::class, 'Devices')) {
            $targetPathPart = 'devices';
        }

        return $this->fileUploader->moveAllUploadedFiles($user->getUserIdentifier(), $targetPathPart, $object->getId());
    }
}
