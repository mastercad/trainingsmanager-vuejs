<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Devices;
use DirectoryIterator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;

use function array_merge;
use function is_dir;
use function preg_replace;

#[AsController]
final class DeviceImageController extends AbstractController
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

        $device = $entityManager->getRepository(Devices::class)->findOneBy(['id' => $id]);

        if (! $device instanceof Devices) {
            throw $this->createNotFoundException('Resource not found');
        }

        return $this->json(
            array_merge(
                $this->collectExistingImages($device->getId()),
                $this->collectUploadedImages()
            )
        );
    }

    /** @return mixed[] */
    private function collectExistingImages(int $deviceId): array
    {
        $images = [];
        $imageDir = $this->dynamicContentDirectory . '/devices/' . $deviceId;

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
