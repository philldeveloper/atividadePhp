<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Form\RegistrationFormType;

#[IsGranted('ROLE_USER')]
#[Route('/user')]
class UserController extends AbstractController
{   
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/', name: 'app_user', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {        
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);

    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user, AuthorizationCheckerInterface $authChecker): Response
    {   
        
        if($this->isGranted('ROLE_USER')){
            return $this->render('user/show.html.twig', [
                'user' => $this->getUser(),
            ]);
        }
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, UserRepository $userRepository): Response
    {
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
        $userSelected = $userRepository->find($user);

        if ($form->isSubmitted() && $form->isValid()) { 

            //campos para editar todos, menos admin e gerente, pois não possuem cliente.
            if($user->getClient()){
                $user->getClient()->setAddress($form->get('address')->getData());
                $user->getClient()->setPhone($form->get('phone')->getData());
            }

            $userRepository->save($user, true);

            $this->addFlash('update', 'Usuário atualizado com sucesso.');
            return $this->redirectToRoute('app_user', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
            'address' => $userSelected->getClient() ? $userSelected->getClient()->getAddress() : '',
            'phone' => $userSelected->getClient() ? $userSelected->getClient()->getPhone() : '',
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user, true);
        }

        $this->addFlash('success', 'Usuário removido com sucesso.');
        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
