<?php

declare(strict_types=1);

namespace App\Serializer;

use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class ExerciseNormalizer implements NormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    private const ALREADY_CALLED = 'EXERCISES_OBJECT_NORMALIZER_ALREADY_CALLED';

    /** @return mixed[] */
    public function getSupportedTypes(string|null $format): array
    {
        return [];
    }

    /** @param mixed[] $context */
    public function supportsNormalization(mixed $data, string|null $format = null, array $context = []): bool
    {
//      return !isset($context[self::ALREADY_CALLED]) && $data instanceof Exercises;
        return ! isset($context[self::ALREADY_CALLED]);
    }

    /** @param mixed[] $context */
    public function normalize(mixed $object, string|null $format = null, array $context = []): mixed
    {
        $context[self::ALREADY_CALLED] = true;

//        $object->setPreviewPicturePath($this->fileUploader->retrieveUrl($object->getPreviewPicturePath()) ?? '');

        return $this->normalizer->normalize($object, $format, $context);
    }
}
