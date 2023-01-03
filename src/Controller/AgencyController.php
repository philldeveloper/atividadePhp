<?php

namespace App\Controller;

use App\Entity\Agency;
use App\Form\AgencyType;
use App\Repository\AgencyRepository;
use App\Repository\ManagerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

#[IsGranted('ROLE_USER')]
#[Route('/agency')]
class AgencyController extends AbstractController
{
    #[Route('/', name: 'app_agency_index', methods: ['GET'])]
    public function index(AgencyRepository $agencyRepository, ManagerRepository $managerRepository, AuthorizationCheckerInterface $authChecker): Response
    {
        // $user = $transactionRepository->findBy(['id' => 2]);
        // dd($user->getAccount()->getNumber());
        // dd($user->getId());

        // /** @var \App\Entity\User $user */
        // $user = $this->getUser();
        // // dd($user->getId());
        // // dd($agency->getManager()->getId());
        // dd($managerRepository->findBy(['id' => $user->getId()]));
        // if ($authChecker->isGranted('ROLE_ADMIN')) {
        //     return $this->render('agency/index.html.twig', [
        //         // 'agencies' => $agencyRepository->findBy(['manager_id' => $user->getId()]),
        //         'agencies' => $agencyRepository->findBy(['manager_id' => $user->getId()]),
        //     ]);
        // }

        return $this->render('agency/index.html.twig', [
            'agencies' => $agencyRepository->findAll(),
        ]);
    }

    #[IsGranted('ROLE_SUPER_ADMIN')]
    #[Route('/new', name: 'app_agency_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AgencyRepository $agencyRepository): Response
    {
        $agency = new Agency();
        $form = $this->createForm(AgencyType::class, $agency);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $agencyRepository->save($agency, true);

            return $this->redirectToRoute('app_agency_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('agency/new.html.twig', [
            'agency' => $agency,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_agency_show', methods: ['GET'])]
    public function show(Agency $agency): Response
    {
        return $this->render('agency/show.html.twig', [
            'agency' => $agency,
        ]);
    }


    #[IsGranted('ROLE_ADMIN')]
    #[Route('/{id}/edit', name: 'app_agency_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Agency $agency, AgencyRepository $agencyRepository): Response
    {
        $form = $this->createForm(AgencyType::class, $agency);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $agencyRepository->save($agency, true);

            return $this->redirectToRoute('app_agency_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('agency/edit.html.twig', [
            'agency' => $agency,
            'form' => $form,
        ]);
    }

    #[IsGranted('ROLE_SUPER_ADMIN')]
    #[Route('/{id}', name: 'app_agency_delete', methods: ['POST'])]
    public function delete(Request $request, Agency $agency, AgencyRepository $agencyRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $agency->getId(), $request->request->get('_token'))) {
            $agencyRepository->remove($agency, true);
        }

        return $this->redirectToRoute('app_agency_index', [], Response::HTTP_SEE_OTHER);
    }
}
