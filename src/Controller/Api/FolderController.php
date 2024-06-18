<?php

namespace App\Controller\Api;

use App\Entity\EasyMedia\Folder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class FolderController extends AbstractController
{
    #[Route('/api/folders', methods: ['GET'])]
    public function getFolders(EntityManagerInterface $entityManager)
    {
        $folderRepository = $entityManager->getRepository(Folder::class);
        $folders = $folderRepository->findAll();

        return new JsonResponse([
            'success' => true,
            'items' => collect($folders)->map(fn (Folder $folder) => ['id' => $folder->getId(), 'slug' => $folder->getSlug(), 'name' => $folder->getName()])
        ]);
    }
}