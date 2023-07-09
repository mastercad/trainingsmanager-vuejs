<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Devices;
use DirectoryIterator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;

use function is_dir;
use function is_numeric;
use function preg_replace;

#[AsController]
final class DeviceImageController extends AbstractController
{
    public function __invoke(EntityManagerInterface $entityManager, string $dynamicContentDirectory, string $uploadsDirectory, int $id = 0): JsonResponse
    {
        $images = [];

        if (is_numeric($id)) {
            $device = $entityManager->getRepository(Devices::class)->findOneBy(['id' => $id]);

            if (! $device instanceof Devices) {
                throw $this->createNotFoundException('Resource not found');
            }

            $imageDir = $dynamicContentDirectory . '/devices/' . $device->getId();

            if (is_dir($imageDir)) {
                $directoryIterator = new DirectoryIterator($imageDir);
                foreach ($directoryIterator as $file) {
                    if (! $file->isFile()) {
                        continue;
                    }

                    $images[] = '/' . preg_replace('/^.*\/public\//', '', $file->getPathname());
                }
            }
        }

        $directoryIterator = new DirectoryIterator($uploadsDirectory . '/' . $this->getUser()->getUserIdentifier());
        foreach ($directoryIterator as $file) {
            if (! $file->isFile()) {
                continue;
            }

            $images[] = '/' . preg_replace('/^.*\/public\//', '', $file->getPathname());
        }

        return $this->json($images);
    }
}
