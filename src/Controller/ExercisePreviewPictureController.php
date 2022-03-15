<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use App\Entity\Exercises;
use App\Service\FileUploader;

#[AsController]
final class ExercisePreviewPictureController extends AbstractController
{
    public function __invoke(Request $request, FileUploader $fileUploader): Exercises
    {
        $uploadedFile = $request->files->get('file');
        if (!$uploadedFile) {
            throw new BadRequestHttpException('"file" is required');
        }

        // create a new entity and set its values
        $exercise = new Exercises();
        $exercise->setName($request->get('name'));
        $exercise->setDescription($request->get('description') ?? '');
        $exercise->setSeoLink($request->get('seoLink') ?? '');
        $exercise->setSpecialFeatures($request->get('special-feature') ?? '');

        // upload the file and save its filename
        $exercise->setPreviewPicturePath($fileUploader->upload($uploadedFile, $this->getUser()->getUserIdentifier()));

        return $exercise;
    }
}
