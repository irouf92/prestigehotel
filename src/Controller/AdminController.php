<?php

namespace App\Controller;

use DateTime;
use App\Entity\Membre;
use App\Form\EditMembreType;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin')]
class AdminController extends AbstractController
{
    #[Route('/gestion-des-membres', name:'show_membres', methods: ['GET'])]
    public function showMembre(EntityManagerInterface $entityManager): Response
    {
        $membres = $entityManager->getRepository(Membre::class)->findAll();

        return $this->render('admin/show_membres.html.twig', [
            'membres' => $membres
        ]);
    } //end function show_dashboard()


    #[Route('/modifier-un-membre/{id}', name: 'edit_membre', methods: ['GET', 'POST'])]
    public function editMembre(EntityManagerInterface $entityManager, Membre $membre, Request $request): Response
    {


        $form = $this->createForm(EditMembreType::class, $membre)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $membre->setUpdatedAt(new DateTime());
            $entityManager->persist($membre);
            $entityManager->flush();

            $this->addFlash('success', "La modification de l'utilisateur " . $membre->getNom() . " a été effectué avec succès !");
            return $this->redirectToRoute('show_membres');
        }
        return $this->render('admin/form/edit_membre.html.twig', [
            'form' => $form->createView(),
            'membre' => $membre
        ]);
    } //end function editmembre()

}//end class
