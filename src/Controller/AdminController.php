<?php

namespace App\Controller;

use DateTime;
use App\Entity\Chambre;
use App\Form\ChambreFormType;
use App\Repository\ChambreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    #[Route('/tableau-de-bord', name: 'show_dashboard', methods: ['GET'])]
    public function show_dashboard(EntityManagerInterface $entityManager): Response
    {

        $chambres = $entityManager->getRepository(Chambre::class)->findBy(['deletedAt' => null]);

        return $this->render('admin/show_dashboard.html.twig', [
                'chambres' => $chambres,
        ]);
    } // end function show_dashboard


    #[Route('/ajouter-une-chambre', name: 'create_chambre', methods: ['GET', 'POST'])]
    public function createChambre(ChambreRepository $repository, Request $request): Response 
    {
        // Creation d'une nouvelle chambre
        $chambre = new Chambre();
        // Creation d'un formulaire chambre
        $form = $this->createForm(ChambreFormType::class, $chambre)
                ->handleRequest($request);

        // Condition de validation 
        if($form->isSubmitted() && $form->isValid()) {

            // champs datetime()
            $chambre->setCreatedAt(new DateTime());
            $chambre->setUpdatedAt(new DateTime());


            $repository->add($chambre, true);

            $this->addFlash('success', "La chambre a bien été ajouté, en ligne !");
            return $this->redirectToRoute('show_dashboard');

        } // end if()

            return $this->render('admin/form/chambre.html.twig', [
                'chambre' => $chambre,
                'form' => $form->createView(),
            ]);

    } // end function createChambre

    #[Route('/modifier-une-chambre/{id}', name: 'update_chambre', methods: ['GET', 'POST'])]
    public function updateChambre(Chambre $chambre, Request $request, ChambreRepository $repository): Response
    {

        $form = $this->createForm(ChambreFormType::class, $chambre, [])->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
                $chambre->setUpdatedAt(new DateTime());

                $this->addFlash('success', "La chambre a bien été Modifier, en ligne !");
                return $this->redirectToRoute('show_dashboard');

        } // end if()


        return $this->render('admin/form/chambre.html.twig', [
            'chambre' => $chambre,
            'form' => $form->createView(),
        ]);
    } // end function updateChambre()

    #[Route('/archiver-une-chambre/{id}', name: 'soft_delete_chambre', methods: ['GET'])]
    public function softDeleteChambre(Chambre $chambre, ChambreRepository $repository): Response
    {

        $chambre->setDeletedAt(new DateTime());

        $repository->add($chambre, true);

        $this->addFlash('success', "La chambre a bien été archivé. Voir les archives !");
        return $this->redirectToRoute('show_dashboard');
    } // end softDeleteChambre()

    #[Route('/voir-les-archives', name: 'show_archives', methods: ['GET'])]
    public function showArchives(EntityManagerInterface $entityManager): Response
    {


        $chambres = $entityManager->getRepository(Chambre::class)->findAllArchived();

        return $this->render('admin/show_archives.html.twig', [
            'chambres' => $chambres
        ]);
    } // end showArchives()

    #[Route('/restaurer-un-article/{id}', name: 'restore_article', methods: ['GET'])]
    public function restoreArticle(Chambre $chambre, ChambreRepository $repository): RedirectResponse
    {
        $chambre->setDeletedAt(null);

        $repository->add($chambre, true);

        $this->addFlash('success', "La chambre a bien été restauré !");
        return $this->redirectToRoute('show_archives');
    }// end function restoreArticle()



} // end class Admin
