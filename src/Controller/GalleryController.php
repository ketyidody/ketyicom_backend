<?php

namespace App\Controller;

use App\Entity\EasyMedia\Folder;
use App\Entity\EasyMedia\Media;
use Doctrine\ORM\EntityManagerInterface;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class GalleryController extends AbstractController
{
    #[Route('/portfolio', name: 'galleries_list')]
    public function galleryList(EntityManagerInterface $entityManager, CacheManager $cacheManager): Response
    {
        $folderRepository = $entityManager->getRepository(Folder::class);
        $mediaRepository = $entityManager->getRepository(Media::class);
        $folders = $folderRepository->findBy(['hidden' => false]);

        $foldersArray = collect($folders)->map(fn (Folder $folder) => [
            'id' => $folder->getId(),
            'slug' => $folder->getSlug(),
            'name' => $folder->getName(),
            'preview' => $cacheManager->getBrowserPath(
                $mediaRepository->findOneBy(['folder' => $folder])->getPath(),
                'web480'
            ),
        ])->toArray();

        return $this->render('gallery/list.html.twig', [
            'galleries' => $foldersArray,
        ]);
    }

    #[Route('/portfolio/{galleryId}', name: 'gallery_detail')]
    public function galleryDetail(
        int $galleryId,
        EntityManagerInterface $entityManager,
        CacheManager $cacheManager
    ): Response {
        $mediaRepository = $entityManager->getRepository(Media::class);
        $images = $mediaRepository->findBy(['folder' => $galleryId]);

        $imagesArray = collect($images)->map(fn (Media $image) => [
            'id' => $image->getId(),
            'name' => $image->getMeta('title'),
            'description' => $image->getMeta('description'),
            'thumbnail' => $cacheManager->getBrowserPath($image->getPath(), 'web480'),
            'path' => $cacheManager->getBrowserPath($image->getPath(), 'web1600'),
        ]);

        return $this->render('gallery/detail.html.twig', [
            'images' => $imagesArray,
        ]);
    }
}
