<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Exercises;
use DirectoryIterator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;

use function array_merge;
use function is_dir;
use function preg_replace;

#[AsController]
final class ExerciseImageController extends AbstractController
{
    private string $dynamicContentDirectory;
    private string $uploadsDirectory;

    public function __invoke(
        EntityManagerInterface $entityManager,
        string $dynamicContentDirectory,
        string $uploadsDirectory,
        int $id = 0
    ): JsonResponse {
        $this->dynamicContentDirectory = $dynamicContentDirectory;
        $this->uploadsDirectory = $uploadsDirectory;

        $exercise = $entityManager->getRepository(Exercises::class)->findOneBy(['id' => $id]);

        if (! $exercise instanceof Exercises) {
            throw $this->createNotFoundException('Resource not found');
        }

        return $this->json(
            array_merge(
                $this->collectExistingImages($exercise->getId()),
                $this->collectUploadedImages()
            )
        );
    }

    /** @return mixed[] */
    private function collectExistingImages(int $exerciseId): array
    {
        $images = [];
        $imageDir = $this->dynamicContentDirectory . '/exercises/' . $exerciseId;

        if (is_dir($imageDir)) {
            $directoryIterator = new DirectoryIterator($imageDir);
            foreach ($directoryIterator as $file) {
                if (! $file->isFile()) {
                    continue;
                }

                $images[] = '/' . preg_replace('/^.*\/public\//', '', $file->getPathname());
            }
        }

        return $images;
    }

    /** @return mixed[] */
    private function collectUploadedImages(): array
    {
        $images = [];
        $uploadsDirectory = $this->uploadsDirectory . '/' . $this->getUser()->getUserIdentifier();
        if (is_dir($uploadsDirectory)) {
            foreach (new DirectoryIterator($uploadsDirectory) as $file) {
                if (! $file->isFile()) {
                    continue;
                }

                $images[] = '/' . preg_replace('/^.*\/public\//', '', $file->getPathname());
            }
        }

        return $images;
    }
}
