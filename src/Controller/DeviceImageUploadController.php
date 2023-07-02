<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

#[AsController]
final class DeviceImageUploadController extends AbstractController
{
    public function __invoke(Request $request, FileUploader $fileUploader): Response
    {
        $uploadedFiles = $request->files->get('deviceImage');
        if (! $uploadedFiles) {
            throw new BadRequestHttpException('"file" is required');
        }

        foreach ($uploadedFiles as $uploadedFile) {
            // upload the file
            $fileUploader->upload($uploadedFile, $this->getUser()->getUserIdentifier());
        }

        return $this->json(['success' => true]);
    }
}
