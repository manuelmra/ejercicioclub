<?php

namespace App\Serializer;

use App\Entity\Coach;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class CoachNormalizer implements ContextAwareNormalizerInterface
{
    private $normalizer;

    public function __construct(
        ObjectNormalizer $normalizer
    ) {
        $this->normalizer = $normalizer;
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