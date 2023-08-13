<?php

declare(strict_types=1);

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

use function is_object;
use function method_exists;
use function preg_match;
use function preg_replace;
use function strtolower;

class SeoLinkHandler
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function handleSeoLinkForCreate(mixed $entity): mixed
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

        return $entity->getSeoLink();
    }

    public function handleSeoLinkForUpdate(mixed $entity): mixed
    {
        if (
            ! is_object($entity)
            || ! method_exists($entity, 'getSeoLink')
            || ! method_exists($entity, 'setSeoLink')
        ) {
            return null;
        }

        if (empty($entity->getSeoLink())) {
            return $this->handleSeoLinkForCreate($entity);
        }

        $repository = $this->entityManager->getRepository($entity::class);

        $newSeoLink = $this->convertToSnakeCase($entity->getName());
        if ($newSeoLink !== $entity->getSeoLink()) {
            $entity->setSeoLink($newSeoLink);
            $existingSeoLink = $repository->findOneBy(['seoLink' => $entity->getSeoLink()]);
            if ($existingSeoLink) {
                $entity->setSeoLink($this->incrementSeoLink($entity->getSeoLink()));

                return $this->handleSeoLinkForCreate($entity);
            }
        }

        return $entity->getSeoLink();
    }

    private function convertToSnakeCase(string $name): string
    {
        return preg_replace(
            '/[^\p{L}0-9\s ]+/u',
            '_',
            strtolower(
                preg_replace(
                    '/(?<!^)[A-Z]/',
                    '_$0',
                    preg_replace(
                        '/[ ]+/',
                        '_',
                        $name
                    )
                )
            )
        );
    }

    private function incrementSeoLink(string $seoLink): string
    {
        if (preg_match('/(.*?)_(\d+)$/', $seoLink, $matches)) {
            return $matches[1] . '_' . (++$matches[2]);
        }

        return $seoLink . '_1';
    }
}
