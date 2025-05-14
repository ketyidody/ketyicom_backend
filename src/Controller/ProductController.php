<?php

namespace App\Controller;

use App\Entity\EasyMedia\Media;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProductController extends AbstractController
{
    #[Route('/products', name: 'products_list')]
    public function productList(EntityManagerInterface $entityManager, CacheManager $cacheManager): Response
    {
        $mediaRepository = $entityManager->getRepository(Media::class);
        $products = $entityManager->getRepository(Product::class)->findAll();
        $productsArray = array_map(function(Product $product) use ($mediaRepository, $cacheManager) {
            $medias = $mediaRepository->findBy(['folder' => $product->getFolder()]);
            $firstPhotoCached = $cacheManager->getBrowserPath(collect($medias)->first()->getPath(), 'web480');

            return [
                'id' => $product->getId(),
                'title' => $product->getTitle(),
                'description' => $product->getDescription(),
                'price' => (float) $product->getPrice(),
                'photo' => $firstPhotoCached,
                'type' => $product->getType(),
            ];
        }, $products);

        return $this->render('product/list.html.twig', [
            'products' => $productsArray,
        ]);
    }

    #[Route('/products/{productId}', name: 'product_detail')]
    public function productDetail(
        int $productId,
        EntityManagerInterface $entityManager,
        CacheManager $cacheManager
    ): Response {
        $mediaRepository = $entityManager->getRepository(Media::class);
        $product = $entityManager->getRepository(Product::class)->find($productId);

        if (empty($product)) {
            return $this->redirectToRoute('products_list');
        }

        $medias = $mediaRepository->findBy(['folder' => $product->getFolder()]);
        $firstPhotoCached = $cacheManager->getBrowserPath(collect($medias)->first()->getPath(), 'web480');
        $mediasCached = collect($medias)->map(function(Media $media) use ($cacheManager) {
            return [
                'name' => $media->getName(),
                'path' => $cacheManager->getBrowserPath($media->getPath(), 'web'),
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
            'photo' => $firstPhotoCached,
            'images' => $mediasCached,
            'type' => $product->getType(),
        ];

        return $this->render('product/detail.html.twig', [
            'product' => $productArray,
        ]);
    }
}
