<?php

namespace App\Controller;

use App\Entity\Funcionario;
use App\Form\FuncionarioType;
use App\Repository\FuncionarioRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/funcionario')]
class FuncionarioController extends AbstractController
{
    #[Route('/', name: 'app_funcionario_index', methods: ['GET'])]
    public function index(FuncionarioRepository $funcionarioRepository): Response
    {        
        return $this->render('funcionario/index.html.twig', [
            'funcionarios' => $funcionarioRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_funcionario_new', methods: ['GET', 'POST'])]
    public function new(Request $request, FuncionarioRepository $funcionarioRepository): Response
    {
        $funcionario = new Funcionario();
        $form = $this->createForm(FuncionarioType::class, $funcionario);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $funcionarioRepository->save($funcionario, true);

            return $this->redirectToRoute('app_funcionario_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('funcionario/new.html.twig', [
            'funcionario' => $funcionario,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_funcionario_show', methods: ['GET'])]
    public function show(Funcionario $funcionario): Response
    {
        return $this->render('funcionario/show.html.twig', [
            'funcionario' => $funcionario,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_funcionario_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Funcionario $funcionario, FuncionarioRepository $funcionarioRepository): Response
    {
        $form = $this->createForm(FuncionarioType::class, $funcionario);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $funcionarioRepository->save($funcionario, true);

            return $this->redirectToRoute('app_funcionario_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('funcionario/edit.html.twig', [
            'funcionario' => $funcionario,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_funcionario_delete', methods: ['POST'])]
    public function delete(Request $request, Funcionario $funcionario, FuncionarioRepository $funcionarioRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$funcionario->getId(), $request->request->get('_token'))) {
            $funcionarioRepository->remove($funcionario, true);
        }

        return $this->redirectToRoute('app_funcionario_index', [], Response::HTTP_SEE_OTHER);
    }
}
