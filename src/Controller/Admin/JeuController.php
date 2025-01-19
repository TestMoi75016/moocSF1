<?php

namespace App\Controller\Admin;

use App\Entity\Jeu;
use App\Form\JeuType;
use App\Repository\JeuRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin', name: 'app_admin_jeu')] /* Préfixage  Cela signifie que toutes les routes définies dans ce contrôleur héritent du nom préfixé app_admin_jeuDans les annotations de route, j'ajoute des suffixes comme '_ajouter', '_modifier', etc. Cela concatène automatiquement ces noms au préfixe app_admin_jeu. Par exemple :
La route /ajouter devient app_admin_jeu_ajouter.*/
class JeuController extends AbstractController
{

    #[Route('/', name: '')] //Route lister par ordre albphabétique en fonction des noms
    public function index(JeuRepository $jeuRepository): Response
    {


        $jeux = $jeuRepository->findBy([], ['nom' => 'ASC']);

        return $this->render('admin/jeu/index.html.twig', [
            'jeux' => $jeux,
        ]);
    }

    #[Route('/ajouter', name: '_ajouter')] // Route Ajouter de nouveau jeux
    #[Route('/modifier/{id}', name: '_modifier')] // Route Modifier de nouveaux jeux
    public function editer(Request $request, JeuRepository $jeuRepository, EntityManagerInterface $entityManager, ?int $id = null): Response
    {


        if ($request->attributes->get('_route') == 'app_admin_jeu_ajouter') {
            $jeu = $jeu = new Jeu();
        } else {
            $jeu = $jeuRepository->find($id);
        }

        $form = $this->createForm(JeuType::class, $jeu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($jeu);
            $entityManager->flush();


            //Gestion du MESSAGE de CONFIRMATION :
            if ($request->attributes->get('_route') == 'app_admin_jeu_ajouter') {

                $this->addFlash(
                    'success',
                    "Le jeu a bien, été ajouté !"
                );
            } else {

                $this->addFlash(
                    'success',
                    'Le jeu a bien été modifié'
                );
            }

            return $this->redirectToRoute('app_admin_jeu');
        }
        return $this->render('admin/jeu/editer.html.twig', [
            'form' => $form, // passage à la vue représentée par la variable 'form' de mon formulaire stockés dans la variable $form
        ]);
    }

    #[Route('/supprimer/{id}', name: '_supprimer')]
    public function supprimer(JeuRepository $jeuRepository, ?int $id, EntityManagerInterface $entityManager ): Response
    {

        $jeux = $jeuRepository->find($id);
        $entityManager->remove($jeux);
        $entityManager->flush();


        $this->addFlash(
            'success',
            message: "Le jeu a bien été supprimé !"
        );

        return $this->redirectToRoute('app_admin_jeu'); // je redirige


    }
}
