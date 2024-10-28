<?php

namespace App\Normalizer;

use App\Entity\Recette;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class PaginationNormalizer implements NormalizerInterface
{
    public function __construct(
        #[Autowire(service: 'serializer.normalizer.object')]
        private readonly NormalizerInterface $normalizer
        )
    {
    }

    public function normalize(mixed $object, ?string $format = null, array $context = []): array|bool|float|int|string|\ArrayObject|null
    {
        if (!($object instanceof PaginationInterface)) {
            throw new \RuntimeException();
        }

        return [
            'items' => array_map(fn (Recette $recette) => $this->normalizer->normalize($recette, $format, $context),
                                 $object->getItems()),
            'total' => $object->getTotalItemCount(),
            'count' => $object->getItemNumberPerPage(),
            'lastPage' => ceil($object->getTotalItemCount() / $object->getItemNumberPerPage()),

        ];
    }

    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return ($data instanceof PaginationInterface);
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            PaginationInterface::class => true
        ];
    }
}