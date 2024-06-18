<?php

namespace App\Controller;

use Adeliom\EasyMediaBundle\Twig\EasyMediaRuntime;
use App\Entity\EasyMedia\Media;
use Doctrine\ORM\EntityManagerInterface;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomepageController extends AbstractController
{
    #[Route('/', name: 'app_homepage')]
    public function index(EntityManagerInterface $entityManager, CacheManager $cacheManager): Response
    {
        $mediaRepository = $entityManager->getRepository(Media::class);
        $media = $mediaRepository->find(4);

//        dump($media->getThumbnailPath());

        $thumb =  $cacheManager->getBrowserPath($media->getPath(), 'thumbnail');

        return $this->render('homepage/index.html.twig', [
            'controller_name' => 'HomepageController',
            'media' => $media,
            'thumb' => $thumb,
        ]);
    }
}
