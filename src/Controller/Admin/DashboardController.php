<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Entity\Order;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Shop Admin');
    }

    public function configureCrud(): Crud
    {
        return parent::configureCrud()
            ->addFormTheme('@EasyMedia/form/easy-media.html.twig')
            ;
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

//         Shop section
        yield MenuItem::section('Shop');
        yield MenuItem::linkToCrud('Products', 'fa fa-shopping-bag', Product::class);
        yield MenuItem::linkToCrud('Orders', 'fa fa-shopping-cart', Order::class);

//         Media section
        yield MenuItem::section('Media');
        yield MenuItem::linkToRoute('Media Library', 'fa fa-image', 'media.index');
    }
}
