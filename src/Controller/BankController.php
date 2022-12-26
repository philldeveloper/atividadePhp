<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BankController extends AbstractController
{
    #[Route('/bank', name: 'app_bank')]
    public function index(): Response
    {
        return $this->render('bank/index.html.twig', [
            'controller_name' => 'BankController',
        ]);
    }
}
