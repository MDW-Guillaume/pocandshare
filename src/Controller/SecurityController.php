<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils, Security $security): Response
    {
        if($security->getUser()){
            return new RedirectResponse($this->generateUrl('home'));
        }
        $error = $authenticationUtils->getLastAuthenticationError();

        return $this->render('security/login.html.twig', ['error' => $error]);
    }

    #[Route(path: '/register', methods: ['GET'] , name: 'app_register')]
    public function register(AuthenticationUtils $authenticationUtils, Security $security): Response
    {
        if($security->getUser()){
            return new RedirectResponse($this->generateUrl('home'));
        }

        $error = $authenticationUtils->getLastAuthenticationError();

        return $this->render('security/register.html.twig', ['error' => $error]);
    }
    
    #[Route(path: '/register', methods: ['POST'] ,name: 'app_save_user')]
    public function saveUser(Request $request, EntityManagerInterface $manager, Security $security): Response
    {
        if($security->getUser()){
            return new RedirectResponse($this->generateUrl('home'));
        }
        
        $user = new User();
        $user->setName($request->get('name'));
        $user->setEmail($request->get('email'));
        $user->setPassword($this->passwordHasher->hashPassword($user,$request->get('password')));
        $manager->persist($user);
        
        $manager->flush();
        return $this->render('security/register.html.twig', []);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('');
    }
}
