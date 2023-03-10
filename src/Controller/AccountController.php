<?php

namespace App\Controller;

use App\Entity\Account;
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
        }

        if ($authChecker->isGranted('ROLE_MANAGER')) {
            return $this->render('account/index.html.twig', [
                'accounts' => $accountRepository->findAll(),
            ]);
        }

        $filteredAccounts = array_filter($accounts->getValues(), function ($acc) {
            return $acc->getStatus() != 3;
        });

        return $this->render('account/index.html.twig', [
            'accounts' => $filteredAccounts,
        ]);
    }

    #[Route('/new', name: 'app_account_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AccountRepository $accountRepository, AuthorizationCheckerInterface $authChecker): Response
    {
        $account = new Account();
        $account->setNumber(rand(1000, 9999));

        $form = $this->createForm(AccountType::class, $account);
        $form->handleRequest($request);

        $user = $this->getUser();

        if ($authChecker->isGranted('ROLE_USER') && count($user->getRoles()) == 1) {

            $client = $user->getClient();

            $account->addClient($client);

            if ($client->isActive()) {
                // $account->setIsActive(1);
                // $form->getData()->setIsActive(1);
                $account->setStatus(1);
            } else {
                // $account->setIsActive(0);
                // $form->getData()->setIsActive(0);
                $account->setStatus(0);
            }
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $accountRepository->save($account, true);

            $this->addFlash('success', 'Conta criada com sucesso.');

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
        if ($account->getStatus() == 3) {
            throw new AccessDeniedException('Unable to edit this account! Status: encerrada');
        }

        $form = $this->createForm(AccountType::class, $account);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $accountRepository->save($account, true);

            $this->addFlash('update', 'Conta atualizada com sucesso.');

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
        if ($authChecker->isGranted('ROLE_MANAGER')) {
            if ($this->isCsrfTokenValid('delete' . $account->getId(), $request->request->get('_token'))) {
                // $accountRepository->remove($account, true);
                $account->setStatus(3); // encerrada
                $this->addFlash('success', 'Conta encerrada com sucesso.');
            }
        } else if ($authChecker->isGranted('ROLE_USER')) {
            $account->setStatus(2); // aguardando encerramento
            $this->addFlash('success', 'solicita????o de conta realizada com sucesso.');
        }

        $accountRepository->save($account, true);

        return $this->redirectToRoute('app_account_index', [], Response::HTTP_SEE_OTHER);
    }
}
