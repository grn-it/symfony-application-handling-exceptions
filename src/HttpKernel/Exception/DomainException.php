<?php

declare(strict_types=1);

namespace App\HttpKernel\Exception;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class DomainException extends \Exception implements HttpExceptionInterface
{
    public function __construct(
        string $message = "",
        private readonly int $status = Response::HTTP_BAD_REQUEST,
        int $code = 0,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function getStatusCode(): int
    {
        return $this->status;
    }
    
    public function getHeaders(): array
    {
        return [];
    }
}
