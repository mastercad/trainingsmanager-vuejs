<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use App\Service\FileUploader;
use Symfony\Component\HttpFoundation\Response;

#[AsController]
final class ExerciseImageDeleteController extends AbstractController
{
    public function __invoke(Request $request, FileUploader $fileUploader, string $fileName): Response
    {
        $fileUploader->delete($fileName, $this->getUser()->getUserIdentifier());

        return $this->json(['success' => true]);
    }
}
