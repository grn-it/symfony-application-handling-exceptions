<?php

namespace App\Controller;

use App\Entity\News;
use App\Repository\NewsRepository;
use App\Service\News\NewsPersister;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class NewsController extends AbstractController
{
    public function __construct(private readonly SerializerInterface $serializer) {}

    #[Route('/news', 'news_add', methods: ['POST'])]
    public function add(Request $request, NewsPersister $newsPersister): JsonResponse
    {
        $news = $this->serializer->deserialize($request->getContent(), News::class, 'json');

        $news = $newsPersister->persist($news);
        
        return $this->json($news);
    }
}
