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

class FolderController extends AbstractController
{
    #[Route('/api/folders', methods: ['GET'])]
    public function getFolders(EntityManagerInterface $entityManager, CacheManager $cacheManager): Response
    {
        $folderRepository = $entityManager->getRepository(Folder::class);
        $mediaRepository = $entityManager->getRepository(Media::class);
        $folders = $folderRepository->findAll();

        return new JsonResponse([
            'success' => true,
            'items' => collect($folders)->map(fn (Folder $folder) => [
                'id' => $folder->getId(),
                'slug' => $folder->getSlug(),
                'name' => $folder->getName(),
                'folderThumbnailPath' => $cacheManager->getBrowserPath(
                    $mediaRepository->findOneBy(['folder' => $folder])->getPath(),
                    'thumbnail'
                ),
            ])
        ]);
    }
}
