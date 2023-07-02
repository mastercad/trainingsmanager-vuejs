<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;

use function base64_decode;

#[AsController]
final class UploadImageDeleteController extends AbstractController
{
    public function __invoke(Request $request, FileUploader $fileUploader, string $fileName): Response
    {
        return $this->json(
            [
                'success' => $fileUploader->delete(
                    base64_decode($fileName),
                    $this->getUser()->getUserIdentifier()
                )
            ]
        );
    }
}
