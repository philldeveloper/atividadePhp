<?php

namespace App\Controller;

use App\Entity\Transaction;
use App\Form\TransactionType;
use App\Repository\TransactionRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

#[IsGranted('PUBLIC_ACCESS')]
#[Route('/transaction')]
class TransactionController extends AbstractController
{
    #[Route('/', name: 'app_transaction_index', methods: ['GET'])]
    public function index(TransactionRepository $transactionRepository): Response
    {
        // $user = $transactionRepository->findBy(['id' => 2]);
        // dd($user->getAccount()->getNumber());
        // dd($user->getId());

        return $this->render('transaction/index.html.twig', [
            'transactions' => $transactionRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_transaction_new', methods: ['GET', 'POST'])]
    public function new(Request $request, TransactionRepository $transactionRepository, AuthorizationCheckerInterface $authChecker): Response
    {

        if (false !== $authChecker->isGranted('ROLE_SUPER_ADMIN')) {
            throw new AccessDeniedException('Unable to access this page!');
        }

        $transaction = new Transaction();
        $form = $this->createForm(TransactionType::class, $transaction);
        $form->handleRequest($request);

        $transaction->setDate(new DateTime());

        if ($form->isSubmitted() && $form->isValid()) {

            $operation = (int)$form->getExtraData()['operation'];
            $transaction->setOperation($operation);

            $acountBalance = $transaction->getAccount()->getBalance();
            $transactionValue = $transaction->getValue();

            if ($operation == 1) {  // SAQUE
                if ($transactionValue > $acountBalance) {
                    throw new AccessDeniedException('Valor maior do que o saldo.');
                }
                $transaction->getAccount()->setBalance($acountBalance - $transactionValue);
            } else if ($operation == 2) {   // DEPOSITO
                $transaction->getAccount()->setBalance($acountBalance + $transactionValue);
            } else if ($operation == 3) {   // TRANSFERENCIA
                $targetAccountBalance = $transaction->getTargetAccount()->getBalance();

                if ($transactionValue > $acountBalance) {
                    throw new AccessDeniedException('Valor maior do que o saldo.');
                }
                $transaction->getAccount()->setBalance($acountBalance - $transactionValue);
                $transaction->getTargetAccount()->setBalance($targetAccountBalance + $transactionValue);
            }

            $transactionRepository->save($transaction, true);

            return $this->redirectToRoute('app_transaction_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('transaction/new.html.twig', [
            'transaction' => $transaction,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_transaction_show', methods: ['GET'])]
    public function show(Transaction $transaction): Response
    {
        return $this->render('transaction/show.html.twig', [
            'transaction' => $transaction,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_transaction_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Transaction $transaction, TransactionRepository $transactionRepository, AuthorizationCheckerInterface $authChecker): Response
    {
        throw new AccessDeniedException('Unable to access this page.');

        // if (false !== $authChecker->isGranted('ROLE_SUPER_ADMIN')) {
        //     throw new AccessDeniedException('Unable to access this page!');
        // }

        // $form = $this->createForm(TransactionType::class, $transaction);
        // $form->handleRequest($request);

        // if ($form->isSubmitted() && $form->isValid()) {
        //     $transactionRepository->save($transaction, true);

        //     return $this->redirectToRoute('app_transaction_index', [], Response::HTTP_SEE_OTHER);
        // }

        // return $this->render('transaction/edit.html.twig', [
        //     'transaction' => $transaction,
        //     'form' => $form,
        // ]);
    }

    #[Route('/{id}', name: 'app_transaction_delete', methods: ['POST'])]
    public function delete(Request $request, Transaction $transaction, TransactionRepository $transactionRepository): Response
    {
        throw new AccessDeniedException('Unable to access this page.');
        // if ($this->isCsrfTokenValid('delete' . $transaction->getId(), $request->request->get('_token'))) {
        //     $transactionRepository->remove($transaction, true);
        // }

        // return $this->redirectToRoute('app_transaction_index', [], Response::HTTP_SEE_OTHER);
    }
}
