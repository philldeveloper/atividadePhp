<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// use App\Entity\User;
use App\Repository\UserRepository;

#[IsGranted('ROLE_ADMIN')]
#[Route('/user')]
class UserController extends AbstractController
{   
    #[Route('/', name: 'app_user', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {        
        // dd($userRepository->findAll());

        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);

    }
}
