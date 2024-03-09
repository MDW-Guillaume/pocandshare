<?php

namespace App\Controller\Admin;

use App\Controller\Admin\ProjectCrudController;
use App\Entity\Category;
use App\Entity\Project;
use App\Entity\Tech;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(ProjectCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Poc & Share');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToCrud('Projets', 'fas fa-list', Project::class);
        yield MenuItem::linkToCrud('Catégories', 'fas fa-list', Category::class);
        yield MenuItem::linkToCrud('Technologies', 'fas fa-list', Tech::class);
        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-user', User::class);
    }
}
