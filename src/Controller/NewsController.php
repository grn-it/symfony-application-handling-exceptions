<?php

namespace App\Controller;

use App\Entity\News;
use App\Service\News\NewsPersister;
use App\Service\News\NewsProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class NewsController extends AbstractController
{
    public function __construct(private readonly SerializerInterface $serializer) {}

    #[Route('/news/{id}', 'news_item', methods: ['GET'])]
    public function item(Request $request, int $id, NewsProvider $newsProvider): JsonResponse
    {
        return $this->json(
            $newsProvider->provide($id)
        );
    }
    
    #[Route('/news', 'news_add', methods: ['POST'])]
    public function add(Request $request, NewsPersister $newsPersister): JsonResponse
    {
        $news = $this->serializer->deserialize($request->getContent(), News::class, 'json');

        $news = $newsPersister->persist($news);
        
        return $this->json($news);
    }
}
