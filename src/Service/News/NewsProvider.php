<?php

declare(strict_types=1);

namespace App\Service\News;

use App\Entity\News;
use App\HttpKernel\Exception\DomainException;
use App\Repository\NewsRepository;
use Symfony\Component\HttpFoundation\Response;

class NewsProvider
{
    public function __construct(
        private readonly NewsRepository $newsRepository
    ) {}
    
    public function provide(int $id): News
    {
        $news = $this->newsRepository->find($id);
        if ($news === null) {
            throw new DomainException(
                sprintf('News with id "%d" not found', $id), Response::HTTP_NOT_FOUND
            );
        }
        
        return $news;
    }
}
