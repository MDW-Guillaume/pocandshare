<?php

namespace App\Controller;

use App\Entity\Project;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProfileController extends AbstractController
{
    #[Route('/profil', name: 'profile')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $user = $this->getUser();

        $projectRepository = $doctrine->getRepository(Project::class);
        $myProjects = $projectRepository->findBy(['user' => $user]);
        
        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
            'projet' => $myProjects,
        ]);
    }
}
