<?php

namespace App\Controller;

use App\Entity\Project;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(ManagerRegistry $doctrine): Response
    {
        // Récupérer le repository de l'entité Project
        $projectRepository = $doctrine->getRepository(Project::class);
            
        // Récupérer les 8 derniers projets
        $latestProjects = $projectRepository->findLatest(8);

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'latestProjects' => $latestProjects,
        ]);
    }
}
