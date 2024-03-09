<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Project;
use App\Entity\Tech;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProjectController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/projets', methods: ['GET'], name: 'project_index')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $projectRepository = $doctrine->getRepository(Project::class);
        $project = $projectRepository->findLatest(8);

        $techsRepository = $doctrine->getRepository(Tech::class);
        $tech = $techsRepository->findAll();

        $categoryRepository = $doctrine->getRepository(Category::class);
        $category = $categoryRepository->findAll();

        return $this->render('project/index.html.twig', [
            'controller_name' => 'ProjectController',
            'project' => $project,
            'tech' => $tech,
            'category' => $category,
        ]);
    }

    #[Route('/projets', methods: ['POST'], name: 'project_filters')]
    public function handlePost(Request $request, ManagerRegistry $doctrine): Response
    {
        $entityManager = $this->entityManager;
        $projectRepository = $doctrine->getRepository(Project::class);
        $filteredProject = $projectRepository->findAll();
        $filter_tech = $request->request->get('tech');
        $filter_category = $request->request->get('category');

        $techRepository = $doctrine->getRepository(Tech::class);
        $tech = $techRepository->findAll();

        $categoryRepository = $doctrine->getRepository(Category::class);
        $category = $categoryRepository->findAll();

        // Filtre techs
        if (!empty($filter_tech)) {
            $queryBuilder = $projectRepository->createQueryBuilder('p')
                ->leftJoin('p.tech', 't')
                ->andWhere('t.id = :tech_id') 
                ->setParameter('tech_id', $filter_tech);

            $filteredProject = $queryBuilder->getQuery()->getResult();
        }

        // Filtre Category

        if (!empty($filter_category)) {
            foreach ($filteredProject as $key => $project) {
                $categoryId = $project->getCategory()->getId();

                if ($categoryId != $filter_category) {
                    unset($filteredProject[$key]);
                }
            }
        }


        return $this->render('project/index.html.twig', [
            'controller_name' => 'ProjectController',
            'project' => $filteredProject,
            'tech' => $tech,
            'category' => $category,
        ]);
    }
}
