<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Project;
use App\Entity\Tech;
use App\Form\ProjectType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

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
    
    #[Route('/projet/add', name: 'projet_add')]
    public function create(Security $security, ManagerRegistry $doctrine, Request $request, SluggerInterface $slugger): Response
    {
        $project = new Project();
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $security->getUser();
            $project->setUser($user);

            $thumbnailFile = $form->get('thumbnail')->getData();

            if ($thumbnailFile) {
                $originalFilename = pathinfo($thumbnailFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$thumbnailFile->guessExtension();
    
                $thumbnailFile->move(
                    $this->getParameter('project_images_directory'),
                    $newFilename
                );
    
                $project->setThumbnail($newFilename);
            }
            $entityManager = $doctrine->getManager();
            $entityManager->persist($project);
            $entityManager->flush();

            return $this->redirectToRoute('profile');
        }

        return $this->render('project/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/projet/{id}/edit', name: 'projet_edit')]
    public function edit(Security $security, ManagerRegistry $doctrine, Request $request, SluggerInterface $slugger, $id): Response
    {
        $project = $doctrine->getRepository(Project::class)->find($id);

        // Aucun projet trouvé
        if (!$project) {
            return new RedirectResponse($this->generateUrl('home'));
        }

        // Utilisateur non connecté
        if(!$security->getUser()){
            return new RedirectResponse($this->generateUrl('home'));
        }

        // Utilisateur non créateur

        if($security->getUser() != $project->getUser()){
            return new RedirectResponse($this->generateUrl('home'));
        }        

        $form = $this->createForm(ProjectType::class, $project);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $doctrine->getManager()->flush();

            return $this->redirectToRoute('project_list');
        }

        return $this->render('project/edit.html.twig', [
            'form' => $form->createView(),
        ]);

    }   
    
    #[Route('/projet/{id}/show', name: 'projet_show')]
    public function show(Security $security, ManagerRegistry $doctrine, Request $request, SluggerInterface $slugger, $id): Response
    {
        $project = $doctrine->getRepository(Project::class)->find($id);

        return $this->render('project/show.html.twig', [
            'project' => $project,
        ]);
    }   
}
