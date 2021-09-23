<?php

namespace App\Controller;

use App\Entity\Domaine;
use App\Repository\DomaineRepository;
use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UtilisateurConnecteController extends AbstractController
{
    #[Route('/profils', name: 'profils')]
    public function profis(UtilisateurRepository $utilisateurRepository, DomaineRepository $domaineRepository): Response
    {
        $utilisateurs = $utilisateurRepository->findAll();

        return $this->render('util_connecte/profils.html.twig', [
            'utils' => $utilisateurs,
        ]);
    }
}

