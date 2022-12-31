<?php

namespace App\Controller;

use App\Entity\Manager;
use App\Form\ManagerType;
use App\Repository\ManagerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
#[Route('/manager')]
class ManagerController extends AbstractController
{
    #[Route('/', name: 'app_manager_index', methods: ['GET'])]
    public function index(ManagerRepository $managerRepository): Response
    {
        return $this->render('manager/index.html.twig', [
            'managers' => $managerRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_manager_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ManagerRepository $managerRepository): Response
    {
        $manager = new Manager();
        $form = $this->createForm(ManagerType::class, $manager);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $managerRepository->save($manager, true);

            return $this->redirectToRoute('app_manager_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('manager/new.html.twig', [
            'manager' => $manager,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_manager_show', methods: ['GET'])]
    public function show(Manager $manager): Response
    {
        return $this->render('manager/show.html.twig', [
            'manager' => $manager,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_manager_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Manager $manager, ManagerRepository $managerRepository): Response
    {
        $form = $this->createForm(ManagerType::class, $manager);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $managerRepository->save($manager, true);

            return $this->redirectToRoute('app_manager_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('manager/edit.html.twig', [
            'manager' => $manager,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_manager_delete', methods: ['POST'])]
    public function delete(Request $request, Manager $manager, ManagerRepository $managerRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$manager->getId(), $request->request->get('_token'))) {
            $managerRepository->remove($manager, true);
        }

        return $this->redirectToRoute('app_manager_index', [], Response::HTTP_SEE_OTHER);
    }
}
