<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientType;
use App\Repository\ClientRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

#[IsGranted('ROLE_ADMIN')]
#[Route('/client')]
class ClientController extends AbstractController
{
    /** @var \App\Entity\User $user */

    #[Route('/', name: 'app_client_index', methods: ['GET'])]
    public function index(ClientRepository $clientRepository): Response
    {   
        return $this->render('client/index.html.twig', [
            'clients' => $clientRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_client_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ClientRepository $clientRepository, UserRepository $userRepository): Response
    {
        throw new AccessDeniedException('Route not allowed.');
        
        // $client = new Client();
        // $form = $this->createForm(ClientType::class, $client);
        // $form->handleRequest($request);

        // //pega o id do user logado
        // $userClient = $this->getUser()->getId(); 

        // //retorna uma lista de usuários que não são clientes
        // $usersList = array_filter($userRepository->findAll(), function($el) {
        //     return $el->getClient() == null; //&& !$this->isGranted('ROLE_SUPER_ADMIN')
        // });

        
        // if ($form->isSubmitted() && $form->isValid()) {

        //     //encontra no repositorio o objeto usuário selecionado no front
        //     $formUser = $userRepository->findBy(['id' => (int)$form->getExtraData()['user']]);

        //     /*-------------------------------*/
        //     //seta as contas selecionadas do usuario selecionado como ativa
            
        //     //contas selecionadas:
        //     $contasSelecionadas = $form->getData()->getAccounts();

        //     //usuario selecionado:
        //     //usuario selecionado precisa TER uma conta para se tornar cliente.
        //     dd($formUser);

        //     /*-------------------------------*/

        //     //atribui o objeto usuário para o cliente
        //     $client->setUser($formUser[0]);


        //     $clientRepository->save($client, true);
        //     return $this->redirectToRoute('app_client_index', [], Response::HTTP_SEE_OTHER);
        // }

        // return $this->renderForm('client/new.html.twig', [
        //     'client' => $client,
        //     'form' => $form,
        //     'lista' => $usersList,
        //     'userClient' => $userClient,
        // ]);
    }

    #[Route('/{id}', name: 'app_client_show', methods: ['GET'])]
    public function show(Client $client): Response
    {
        return $this->render('client/show.html.twig', [
            'client' => $client,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_client_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Client $client, ClientRepository $clientRepository, UserRepository $userRepository): Response
    {
        
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            //atribui o objeto usuário para o cliente
            $userSelected = $clientRepository->find($client->getId());

            $client->setUser($userSelected->getUser());
            $form->getData()->setUser($userSelected->getUser());

            $clientRepository->save($client, true);
            return $this->redirectToRoute('app_client_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('client/edit.html.twig', [
            'client' => $client,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_client_delete', methods: ['POST'])]
    public function delete(Request $request, Client $client, ClientRepository $clientRepository, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$client->getId(), $request->request->get('_token'))) {

            // $userSelected = $userRepository->find($client->getUser()->getId());
            
            // //ao inves de deletar, precisamos remover apenas o client do USER.
            // $userSelected->removeClient($client); //?????
            // $userRepository->save($userSelected, true);

            $clientRepository->remove($client, true);
        }

        return $this->redirectToRoute('app_client_index', [], Response::HTTP_SEE_OTHER);
    }
}
