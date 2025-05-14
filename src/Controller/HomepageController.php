<?php

namespace App\Controller;

use Adeliom\EasyMediaBundle\Twig\EasyMediaRuntime;
use App\Entity\EasyMedia\Folder;
use App\Entity\EasyMedia\Media;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomepageController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(EntityManagerInterface $entityManager, CacheManager $cacheManager): Response
    {
        $folderRepository = $entityManager->getRepository(Folder::class);
        $mediaRepository = $entityManager->getRepository(Media::class);
        $folders = $folderRepository->findBy(['hidden' => false]);

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

        $foldersArray = collect($folders)->map(fn (Folder $folder) => [
            'id' => $folder->getId(),
            'slug' => $folder->getSlug(),
            'name' => $folder->getName(),
            'preview' => $cacheManager->getBrowserPath(
                $mediaRepository->findOneBy(['folder' => $folder])->getPath(),
                'web480'
            ),
        ])->toArray();


        return $this->render('homepage/index.html.twig', [
            'controller_name' => 'HomepageController',
            'products' => $productsArray,
            'folders' => $foldersArray,
        ]);
    }
}
