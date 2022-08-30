<?php

declare(strict_types=1);

namespace App\Service\News;

use App\Entity\News;
use App\HttpKernel\Exception\DomainException;
use App\Repository\NewsRepository;

class NewsPersister
{
    public function __construct(
        private readonly NewsRepository $newsRepository
    ) {}
    
    public function persist(News $news): News
    {
        if ($this->newsRepository->findOneBy(['title' => $news->getTitle()])) {
            throw new DomainException(
                sprintf('News with title "%s" already exists', $news->getTitle()),
            );
        }

        $this->newsRepository->add($news, true);
        
        return $news;
    }
}
