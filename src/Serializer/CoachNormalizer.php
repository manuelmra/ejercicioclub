<?php

namespace App\Serializer;

use App\Entity\Coach;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

/**
 *
 *  Normalizers turn objects into arrays and vice versa.
 *  They implement NormalizerInterface for normalizing (object to array)
 *  and DenormalizerInterface for denormalizing (array to object).
 *
 */
class CoachNormalizer implements ContextAwareNormalizerInterface
{
    public function __construct(private readonly ObjectNormalizer $normalizer)
    {
    }

    public function normalize($coach, $format = null, array $context = [])
    {
        $data = $this->normalizer->normalize($coach, $format, $context);
		
		// Add here more fields

        return $data;
    }

    public function supportsNormalization($data, $format = null, array $context = [])
    {
        return $data instanceof Coach;
    }
}