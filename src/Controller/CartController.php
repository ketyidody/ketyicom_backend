<?php

namespace App\Controller;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'cart')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($request->getMethod() === Request::METHOD_POST) {
            /** @var Order $order */
            $order = $entityManager->getRepository(Order::class)->createFromRequest($request);
            $entityManager->persist($order);
            $entityManager->flush();

            return $this->redirectToRoute('successful_order');
        }

        return $this->render('cart/index.html.twig', [
            'controller_name' => 'CartController',
        ]);
    }

    #[Route('/successful-order', name: 'successful_order')]
    public function successfulOrder(): Response
    {
        return $this->render('cart/successful-order.html.twig');
    }
}
