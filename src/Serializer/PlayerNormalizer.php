<?php

namespace App\Serializer;

use App\Entity\Player;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class PlayerNormalizer implements ContextAwareNormalizerInterface
{
    private $normalizer;

    public function __construct(
        ObjectNormalizer $normalizer
    ) {
        $this->normalizer = $normalizer;
    }

    public function normalize($player, $format = null, array $context = [])
    {
        $data = $this->normalizer->normalize($player, $format, $context);
		
		// Add here more fields
        $data['club'] = 'Data about players club';

        return $data;
    }

    public function supportsNormalization($data, $format = null, array $context = [])
    {
        return $data instanceof Player;
    }
}