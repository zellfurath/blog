<?php

namespace App\Controller;

use App\Entity\Domaine;
use App\Form\DomaineType;
use App\Repository\DomaineRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/domaine')]
class AdminDomaineController extends AbstractController
{
    #[Route('/', name: 'admin_domaine_index', methods: ['GET'])]
    public function index(DomaineRepository $domaineRepository): Response
    {
        return $this->render('admin_domaine/index.html.twig', [
            'domaines' => $domaineRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'admin_domaine_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $domaine = new Domaine();
        $form = $this->createForm(DomaineType::class, $domaine);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($domaine);
            $entityManager->flush();

            return $this->redirectToRoute('admin_domaine_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_domaine/new.html.twig', [
            'domaine' => $domaine,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_domaine_show', methods: ['GET'])]
    public function show(Domaine $domaine): Response
    {
        return $this->render('admin_domaine/show.html.twig', [
            'domaine' => $domaine,
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_domaine_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Domaine $domaine): Response
    {
        $form = $this->createForm(DomaineType::class, $domaine);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_domaine_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_domaine/edit.html.twig', [
            'domaine' => $domaine,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_domaine_delete', methods: ['POST'])]
    public function delete(Request $request, Domaine $domaine): Response
    {
        if ($this->isCsrfTokenValid('delete'.$domaine->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($domaine);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_domaine_index', [], Response::HTTP_SEE_OTHER);
    }
}
