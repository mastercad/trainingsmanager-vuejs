<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Exercises;
use DirectoryIterator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpFoundation\JsonResponse;

#[AsController]
final class ExerciseImageController extends AbstractController
{
  public function __invoke(EntityManagerInterface $entityManager, $id = 0): JsonResponse
  {
    $images = [];

    if (is_numeric($id)) {
      $exercise = $entityManager->getRepository(Exercises::class)->findOneBy(['id' => $id]);

      if (!$exercise instanceof Exercises) {
        throw $this->createNotFoundException('Resource not found');
      }

      $imageDir = __DIR__.'/../../public/images/content/dynamic/exercises/'.$exercise->getId();

      if (is_dir($imageDir)) {
          $directoryIterator = new DirectoryIterator($imageDir);
          foreach ($directoryIterator as $file) {
              if ($file->isFile()) {
                  $images[] = '/'.preg_replace('/^.*\/public\//', '', $file->getPathname());
              }
          }
      }
    }

    $directoryIterator = new DirectoryIterator(__DIR__.'/../../public/uploads/'.$this->getUser()->getUserIdentifier());
    foreach ($directoryIterator as $file) {
      if ($file->isFile()) {
        $images[] = "/".preg_replace('/^.*\/public\//', '', $file->getPathname());
      }
    }

    return $this->json($images);
  }
}