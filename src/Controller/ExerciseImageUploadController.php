<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use App\Service\FileUploader;
use Symfony\Component\HttpFoundation\Response;

#[AsController]
final class ExerciseImageUploadController extends AbstractController
{
    public function __invoke(Request $request, FileUploader $fileUploader): Response
    {
        $uploadedFiles = $request->files->get('exerciseImage');
        if (!$uploadedFiles) {
            throw new BadRequestHttpException('"file" is required');
        }

        foreach ($uploadedFiles as $uploadedFile) {
            // upload the file
            $fileUploader->upload($uploadedFile, $this->getUser()->getUserIdentifier());
        }

        return $this->json(['success' => true]);
    }
}
