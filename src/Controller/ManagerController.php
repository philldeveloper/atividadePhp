<?php

namespace App\Controller;

use App\Entity\Manager;
use App\Entity\User;
use App\Form\ManagerType;
use App\Repository\ManagerRepository;
use App\Repository\UserRepository;
use App\Repository\AgencyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


#[IsGranted('ROLE_MANAGER')]
#[Route('/manager')]
class ManagerController extends AbstractController
{
    /** @var \App\Entity\User $user */

    #[Route('/', name: 'app_manager_index', methods: ['GET'])]
    public function index(ManagerRepository $managerRepository): Response
    {
        return $this->render('manager/index.html.twig', [
            'managers' => $managerRepository->findAll(),
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/new', name: 'app_manager_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserPasswordHasherInterface $userPasswordHasher, ManagerRepository $managerRepository, UserRepository $userRepository, AuthorizationCheckerInterface $authChecker): Response
    {
        $manager = new Manager();
        $form = $this->createForm(ManagerType::class, $manager);
        $form->handleRequest($request);

        //pega o id do user logado
        // $userClient = $this->getUser()->getId();


        //retorna uma lista de usuários que não são gerentes
        $managersList = array_filter($userRepository->findAll(), function ($el) {
            return $el->getManager() == null && !$this->isGranted('ROLE_ADMIN');
            // return $el->getManager() == null && $el->getRoles() != 'ROLE_MANAGER';
        });

        // dd($managersList);

        if ($form->isSubmitted() && $form->isValid()) {

            //encontra no repositorio o objeto usuário selecionado no front
            // $formUser = $userRepository->findBy(['id' => (int)$form->getExtraData()['user']]);

            //atribui o objeto usuário para o cliente
            $user = new User();
            $user->setName($form->getExtraData()['name']);
            $user->setEmail($form->getExtraData()['email']);
            $user->setRoles(['ROLE_MANAGER']);
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $manager->setUser($user);

            $managerRepository->save($manager, true);

            $this->addFlash('success', 'Gerente criado com sucesso.');

            return $this->redirectToRoute('app_manager_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('manager/new.html.twig', [
            'manager' => $manager,
            'form' => $form,
            'lista' => $managersList,
            'name' => [],
            'email' => [],
            // 'userClient' => $userClient,
        ]);
    }

    #[Route('/{id}', name: 'app_manager_show', methods: ['GET'])]
    public function show(Manager $manager): Response
    {
        return $this->render('manager/show.html.twig', [
            'manager' => $manager,
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/{id}/edit', name: 'app_manager_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Manager $manager, ManagerRepository $managerRepository, AgencyRepository $agencyRepository): Response
    {
        $form = $this->createForm(ManagerType::class, $manager);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->getUser()->setName($form->getExtraData()['name']);
            $manager->getUser()->setEmail($form->getExtraData()['email']);
            
            // $agencySelected = $agencyRepository->findBy(['id' => (int)$form->getExtraData()['agency']]);
            
            // if($form->getExtraData()['agency'] == '') {
            //     $manager->setAgency(null);
            // }else {
            //     $manager->setAgency($agencySelected[0]);
            // }
            
            $managerRepository->save($manager, true);

            $this->addFlash('update', 'Gerente atualizado com sucesso.');

            return $this->redirectToRoute('app_manager_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('manager/edit.html.twig', [
            'manager' => $manager,
            'form' => $form,
            'name' => $manager->getUser()->getName(),
            'email' => $manager->getUser()->getEmail(),
            'password' => $manager->getUser()->getPassword(),
            'agencies' => $agencyRepository->findAll(),
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/{id}', name: 'app_manager_delete', methods: ['POST'])]
    public function delete(Request $request, Manager $manager, ManagerRepository $managerRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $manager->getId(), $request->request->get('_token'))) {
            $managerRepository->remove($manager, true);
        }

        $this->addFlash('success', 'Gerente removido com sucesso.');

        return $this->redirectToRoute('app_manager_index', [], Response::HTTP_SEE_OTHER);
    }
}
