<?php

declare(strict_types=1);

namespace App\Serializer\Normalizer;

use App\HttpKernel\Exception\DomainException;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ExceptionNormalizer implements NormalizerInterface
{
    public function __construct(#[Autowire('%kernel.debug%')] private readonly bool $debug = false) {}

    /**
     * @param FlattenException $object
     */
    public function normalize(mixed $object, string $format = null, array $context = []): array
    {
        if (!$object instanceof FlattenException) {
            throw new InvalidArgumentException(sprintf('The object must implement "%s".', FlattenException::class));
        }
        
        // in all environments for domain exceptions only a message will be shown
        if ($object->getClass() === DomainException::class) {
            return ['message' => $object->getMessage()];
        }
        
        // in dev and test environment all data of the exception will be shown
        if ($this->debug) {
            return $object->toArray();
        }

        // in prod environment no data will be shown
        return [];
    }
    
    public function supportsNormalization(mixed $data, string $format = null): bool
    {
        return $data instanceof FlattenException;
    }
}
