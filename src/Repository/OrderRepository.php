<?php

namespace App\Repository;

use App\Entity\Order;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    public function createFromRequest(Request $request): Order
    {
        $productRepository = $this->getEntityManager()->getRepository(Product::class);
        $products = $productRepository->findBy(
            [
                'id' => explode(',', $request->get('products')[0])
            ]
        );

        $order = new Order();
        $order->setFirstName($request->get('firstName'));
        $order->setLastName($request->get('lastName'));
        $order->setEmail($request->get('email'));
        $order->setPhone($request->get('phone'));
        $order->setAddress($request->get('address'));
        $order->setOrderItems(new ArrayCollection($products));

        return $order;
    }

}
