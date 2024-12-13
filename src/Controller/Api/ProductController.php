<?php

namespace App\Controller\Api;

use App\Entity\EasyMedia\Media;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/api/products', methods: ['GET'])]
    public function getProducts(EntityManagerInterface $entityManager, CacheManager $cacheManager): Response
    {
        $products = $entityManager->getRepository(Product::class)->findAll();
        $mediasRepository = $entityManager->getRepository(Media::class);

        $productsArray = array_map(function($product) use ($mediasRepository, $cacheManager) {
            $medias = $mediasRepository->findBy(['folder' => $product->getFolder()]);

            $mediasCollection = collect($medias)->map(function(Media $media) use ($cacheManager) {
                return [
                    'title' => $media->getName(),
                    'url' => $cacheManager->getBrowserPath($media->getPath(), 'web'),
                    'thumb' => $cacheManager->getBrowserPath($media->getPath(), 'thumbnail'),
                    'path480' => $cacheManager->getBrowserPath($media->getPath(), 'web480'),
                    'path800' => $cacheManager->getBrowserPath($media->getPath(), 'web800'),
                    'path1280' => $cacheManager->getBrowserPath($media->getPath(), 'web1280'),
                    'path1600' => $cacheManager->getBrowserPath($media->getPath(), 'web1600'),
                ];
            });

            return [
                'id' => $product->getId(),
                'title' => $product->getTitle(),
                'description' => $product->getDescription(),
                'price' => (float) $product->getPrice(),
                'photos' => $mediasCollection->toArray(),
            ];
        }, $products);

        return new JsonResponse($productsArray);
    }
}
