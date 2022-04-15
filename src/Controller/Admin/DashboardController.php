<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Article;
use App\Controller\Admin\UserCrudController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(UserCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Website Skeleton')
            //->setTitle('<img src="..."> ACME <span class="text-small">Corp.</span>'
            ->setFaviconPath('favicon.svg')
            ->setTranslationDomain('my-custom-domain')
            ->setTextDirection('ltr')
            ->renderContentMaximized()
            ->disableUrlSignatures()
            ->generateRelativeUrls();
    }

    public function configureMenuItems(): iterable
    {
        return [
            MenuItem::linkToDashboard('Dashboard', 'fa fa-home'),

            MenuItem::section('Articles'),
            MenuItem::linkToCrud('Article', 'fa fa-tags', Article::class),

            MenuItem::section('Users'),
            MenuItem::linkToCrud('User', 'fa fa-user', User::class),
        ];
    }
}
