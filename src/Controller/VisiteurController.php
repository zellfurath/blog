<?php

namespace App\Controller;

use App\Entity\Publication;
use App\Form\FiltrePublicationType;
use App\Form\PublicationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VisiteurController extends AbstractController
{

    #[Route('/publications', name: 'publications')]
    public function publications(Request $request, EntityManagerInterface $entityManager){

        // ***********
        // FORM FILTRE
        // ***********

        // Liaison entre les données et le formulaire
        $dto = new Publication();
        $formFiltre = $this->createForm( FiltrePublicationType::class, $dto );
        $formFiltre->handleRequest($request);

        // Lister toutes les publications
        $qb = $entityManager->createQueryBuilder();
        $qb->select("p");
        $qb->from("App:Publication", "p");
        $qb->orderBy("p.dateHeure","DESC");

        $query = $qb->getQuery();

        $publications = $query->getResult();

        // **********
        // FORM AJOUT
        // **********

        // Gère le formulaire d'ajout
        $dto = new Publication();
        $formAjout = $this->createForm( PublicationType::class, $dto );
        $formAjout->handleRequest($request);

        if( $formAjout->isSubmitted() )  {

            $dto->setDateHeure(new \DateTime());
            $dto->setUtilisateur($this->getUser());

            $entityManager->persist($dto);
            $entityManager->flush();
        }

        return $this->renderForm('visiteur/publications.html.twig',
            [   'monFormulaire'=>$formFiltre,
                'formAjout'=>$formAjout,
                'lesPublications'=>$publications]);
    }

    #[Route('/', name: 'home')]
    public function home(): Response
    {
        return $this->render('visiteur/home.html.twig',
        ['titre'=>'Coucou', 'contenu'=>'Bienvenue ...']);
    }

}
