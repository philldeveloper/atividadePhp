<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\Client;
use App\Form\AccountType;
use App\Repository\AccountRepository;
use App\Repository\ClientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Doctrine\ORM\EntityManagerInterface;

#[IsGranted('ROLE_USER')]
#[Route('/account')]
class AccountController extends AbstractController
{
    /** @var \App\Entity\User $user */

    #[Route('/', name: 'app_account_index', methods: ['GET'])]
    public function index(AccountRepository $accountRepository, AuthorizationCheckerInterface $authChecker): Response
    {
        $user = $this->getUser();
        $accounts = [];

        if ($user->getClient()) {
            $accounts = $user->getClient()->getAccounts();
        }else if ($user->getClient() == null) {
            $accounts = $user->getAccounts();
        }

        // dd($accountRepository->findAll());

        if ($authChecker->isGranted('ROLE_ADMIN')) {
            return $this->render('account/index.html.twig', [
                'accounts' => $accountRepository->findAll(),
            ]);
        }

        return $this->render('account/index.html.twig', [
            'accounts' => $accounts,
        ]);
    }

    #[Route('/new', name: 'app_account_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AccountRepository $accountRepository, AuthorizationCheckerInterface $authChecker): Response
    {
        $account = new Account();
        
        $form = $this->createForm(AccountType::class, $account);
        $form->handleRequest($request);

        
        $user = $this->getUser();
        
        if ($authChecker->isGranted('ROLE_USER') && count($user->getRoles()) == 1) {
            
            //se for apenas user, seta ele mesmo como user, e deixa cliente em branco
            $form->getData()->addUser($user);
            $account->addUser($user);
            
            /*--------------------------------*/
            $client = $user->getClient();
            
            if($client){
                $account->addClient($client);
                $account->setIsActive(1);
                $form->getData()->setIsActive(1);

            }else if ($client == null){
                //throw new AccessDeniedException('Impossível criar uma conta. Solicite acesso ao gerente da sua agência.');

                $account->setIsActive(0);
                $form->getData()->setIsActive(0);
            }
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $accountRepository->save($account, true);

            return $this->redirectToRoute('app_account_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('account/new.html.twig', [
            'account' => $account,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_account_show', methods: ['GET'])]
    public function show(Account $account): Response
    {
        return $this->render('account/show.html.twig', [
            'account' => $account,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_account_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Account $account, AccountRepository $accountRepository): Response
    {
        $form = $this->createForm(AccountType::class, $account);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $accountRepository->save($account, true);

            return $this->redirectToRoute('app_account_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('account/edit.html.twig', [
            'account' => $account,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_account_delete', methods: ['POST'])]
    public function delete(Request $request, Account $account, AccountRepository $accountRepository, AuthorizationCheckerInterface $authChecker): Response
    {
        if ($authChecker->isGranted('ROLE_ADMIN')) {
            if ($this->isCsrfTokenValid('delete' . $account->getId(), $request->request->get('_token'))) {
                $accountRepository->remove($account, true);
            }
        } else if ($authChecker->isGranted('ROLE_USER')) {
            $account->setIsActive(0);
            $accountRepository->save($account, true);
        }

        return $this->redirectToRoute('app_account_index', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/{id}/activate', name: 'app_account_enable', methods: ['GET', 'POST'])]
    public function enableAccount(Request $request, EntityManagerInterface $entityManager, Account $account, AccountRepository $accountRepository): Response
    {
        /** @var \App\Entity\Client $client */

        $conta = $accountRepository->find($account->getId());

        $userAccount = $conta->getUsers()[0];

        if(count($account->getClients()) == 0) {
            //criar cliente para esta conta

            $client = new Client();
            $client->setName($userAccount->getName());
            $client->setAddress('');
            $client->setPhone(00000000);
            $client->setUser($userAccount);

            $account->addClient($client);

            $entityManager->persist($client);
            $entityManager->persist($userAccount);
            $entityManager->flush();
        }
        

        $account->setIsActive(1);
        $accountRepository->save($account, true);

        return $this->redirectToRoute('app_account_index', [], Response::HTTP_SEE_OTHER);
    }
}
