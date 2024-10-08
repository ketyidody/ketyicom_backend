<?php

namespace App\Controller\Api;

use App\Entity\EasyMedia\Folder;
use App\Entity\EasyMedia\Media;
use Doctrine\ORM\EntityManagerInterface;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GalleryController extends AbstractController
{
    #[Route('/api/main_gallery', methods: ['GET'])]
    public function getMainGallery(EntityManagerInterface $entityManager, CacheManager $cacheManager): Response
    {
        $mediaRepository = $entityManager->getRepository(Media::class);
        $folderRepository = $entityManager->getRepository(Folder::class);
        $mainFolder = $folderRepository->findBy(['slug' => 'main']);

        $medias = $mediaRepository->findBy(['folder' => $mainFolder]);

        return new JsonResponse([
            'success' => true,
            'items' => collect($medias)->map(fn(Media $media, CacheManager $cacheManager) => [
                'id' => $media->getId(),
                'name' => $media->getMeta('title'),
                'description' => $media->getMeta('description'),
                'path' => $media->getPath(),
                'thumbnailPath' => $cacheManager->getBrowserPath($media->getPath(), 'thumbnail'),
            ]),
        ]);
    }

    #[Route('/api/gallery/{gallerySlug}', methods: ['GET'])]
    public function getGallery(EntityManagerInterface $entityManager, CacheManager $cacheManager, string $gallerySlug): Response
    {
        $mediaRepository = $entityManager->getRepository(Media::class);
        $folderRepository = $entityManager->getRepository(Folder::class);
        $mainFolder = $folderRepository->findBy(['slug' => $gallerySlug]);

        $medias = $mediaRepository->findBy(['folder' => $mainFolder]);

        return new JsonResponse([
            'success' => true,
            'items' => collect($medias)->map(fn(Media $media) => [
                'id' => $media->getId(),
                'name' => $media->getMeta('title'),
                'description' => $media->getMeta('description'),
                'path' => $cacheManager->getBrowserPath($media->getPath(), 'web'),
                'thumbnailPath' => $cacheManager->getBrowserPath($media->getPath(), 'thumbnail'),
                'path480' => $cacheManager->getBrowserPath($media->getPath(), 'web480'),
                'path800' => $cacheManager->getBrowserPath($media->getPath(), 'web800'),
                'path1280' => $cacheManager->getBrowserPath($media->getPath(), 'web1280'),
                'path1600' => $cacheManager->getBrowserPath($media->getPath(), 'web1600'),
            ]),
        ]);
    }
}
