<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;

use function base64_decode;
use function file_exists;
use function unlink;

#[AsController]
final class ExerciseImageDeleteController extends AbstractController
{
    public function __invoke(int $id, string $fileName, string $dynamicContentDirectory): Response
    {
        $filePathName = $dynamicContentDirectory . '/exercises/' . $id . '/' . base64_decode($fileName);

        if (! file_exists($filePathName)) {
            return $this->json([
                'success' => true,
                'message' => 'File does not exists!'
            ]);
        }

        return $this->json(['success' => unlink($filePathName)]);
    }
}
