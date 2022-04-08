<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use App\Service\FileUploader;
use Symfony\Component\HttpFoundation\Response;

#[AsController]
final class DeviceImageDeleteController extends AbstractController
{
  public function __invoke(Request $request, FileUploader $fileUploader, string $fileName): Response
  {
    $id = (int) $request->get('id');
    $filePathName = __DIR__.'/../../public/images/content/dynamic/devices/'.$id.'/'.base64_decode($fileName);

    if (!file_exists($filePathName)) {
      return $this->json([
        'success' => true,
        'message' => 'File does not exists!'
      ]);
    }

    return $this->json(['success' => unlink($filePathName)]);
  }
}
