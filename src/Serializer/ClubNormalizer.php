<?php

namespace App\Serializer;

use App\Entity\Club;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class ClubNormalizer implements ContextAwareNormalizerInterface
{
    private $normalizer;

    public function __construct(
        ObjectNormalizer $normalizer
    ) {
        $this->normalizer = $normalizer;
    }

    public function normalize($club, $format = null, array $context = [])
    {
        $data = $this->normalizer->normalize($club, $format, $context);
		
		// Add here more fields

        return $data;
    }

    public function supportsNormalization($data, $format = null, array $context = [])
    {
        return $data instanceof Club;
    }
}