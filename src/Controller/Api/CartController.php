<?php

namespace App\Controller\Api;

use App\Entity\EasyMedia\Media;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    #[Route('/api/cart-product/{productId}', methods: ['GET'])]
    public function getCartProductRow(int $productId, EntityManagerInterface $entityManager, CacheManager $cacheManager): JsonResponse
    {
        $product = $entityManager->getRepository(Product::class)->find($productId);
        $mediasRepository = $entityManager->getRepository(Media::class);

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

        $productArray = [
            'id' => $product->getId(),
            'title' => $product->getTitle(),
            'description' => $product->getDescription(),
            'price' => (float) $product->getPrice(),
            'photo' => $mediasCollection->first(),
            'photos' => $mediasCollection->toArray(),
            'type' => $product->getType(),
        ];

        $html = $this->render('components/cartProductRow.html.twig', [
            'product' => $productArray,
        ]);

        return new JsonResponse($html->getContent());
    }
}
