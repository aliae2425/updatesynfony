<?php

namespace App\Normalizer;

use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class PaginationNormalizer implements NormalizerInterface
{

    public function normalize(mixed $object, ?string $format = null, array $context = []): array|bool|float|int|string|\ArrayObject|null
    {
        if (!($object instanceof PaginationInterface)) {
            throw new \RuntimeException();
        }

        return [
            'total' => $object->getTotalItemCount(),
            'count' => $object->getItemNumberPerPage(),
            'lastPage' => ceil($object->getTotalItemCount() / $object->getItemNumberPerPage()),

        ];
    }

    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return ($data instanceof PaginationInterface && $format == 'json');
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            PaginationInterface::class => true
        ];
    }
}