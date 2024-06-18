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

        $mediaPaths = [];
        /** @var Media $media */
        foreach ($medias as $media) {
            $mediaPaths[$media->getId()] = [
                'id' => $media->getId(),
                'name' => $media->getMeta('title'),
                'description' => $media->getMeta('description'),
                'path' => $media->getPath(),
                'thumbnailPath' => $cacheManager->getBrowserPath($media->getPath(), 'thumbnail'),
            ];
        }

        return new JsonResponse([
            'success' => true,
            'items' => $mediaPaths,
        ]);
    }

    #[Route('/api/gallery/{gallerySlug}', methods: ['GET'])]
    public function getGallery(EntityManagerInterface $entityManager, CacheManager $cacheManager, string $gallerySlug): Response
    {
        $mediaRepository = $entityManager->getRepository(Media::class);
        $folderRepository = $entityManager->getRepository(Folder::class);
        $mainFolder = $folderRepository->findBy(['slug' => $gallerySlug]);

        $medias = $mediaRepository->findBy(['folder' => $mainFolder]);

        $mediaPaths = [];
        /** @var Media $media */
        foreach ($medias as $media) {
            $mediaPaths[$media->getId()] = [
                'id' => $media->getId(),
                'name' => $media->getMeta('title'),
                'description' => $media->getMeta('description'),
                'path' => $media->getPath(),
                'thumbnailPath' => $cacheManager->getBrowserPath($media->getPath(), 'thumbnail'),
            ];
        }

        return new JsonResponse([
            'success' => true,
            'items' => $mediaPaths,
        ]);
    }
}