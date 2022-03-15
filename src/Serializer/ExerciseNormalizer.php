<?php

namespace App\Serializer;

use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use App\Service\FileUploader;
use App\Entity\Exercises;

final class ExerciseNormalizer implements ContextAwareNormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    private FileUploader $fileUploader;
    private const ALREADY_CALLED = 'EXERCISES_OBJECT_NORMALIZER_ALREADY_CALLED';

    public function __construct(FileUploader $fileUploader) {
        $this->fileUploader = $fileUploader;
    }

    public function supportsNormalization($data, ?string $format = null, array $context = []): bool {
        return !isset($context[self::ALREADY_CALLED]) && $data instanceof Exercises;
    }

    public function normalize($object, ?string $format = null, array $context = []) {
        $context[self::ALREADY_CALLED] = true;

        $object->setPreviewPicturePath($this->fileUploader->retrieveUrl($object->getPreviewPicturePath()) ?? '');

        $content = $this->normalizer->normalize($object, $format, $context);

        return $content;
    }
}
