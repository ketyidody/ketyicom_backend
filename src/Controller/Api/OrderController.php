<?php

namespace App\Controller\Api;

use App\Entity\Order;
use App\Entity\Product;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    #[Route('/api/order', methods: ['POST'])]
    public function createOrder(Request $request, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);

        $productRepository = $entityManager->getRepository(Product::class);

        $productIds = collect($data['cart'])->pluck('id')->all();

        $products = $productRepository->findBy(['id' => $productIds]);

        $order = new Order();
        $order->setFirstName($data['user']['firstName']);
        $order->setLastName($data['user']['lastName']);
        $order->setEmail($data['user']['email']);
        $order->setPhone($data['user']['phone']);
        $order->setAddress($data['user']['address']);
        $order->setOrderItems(new ArrayCollection($products));

        $entityManager->persist($order);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Order created successfully'], Response::HTTP_CREATED);
    }
}
