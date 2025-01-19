<?php

namespace App\Controller\Admin;

use App\Entity\Genre;
use App\Form\GenreType;
use App\Repository\GenreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class GenreController extends AbstractController
{
    #[Route('/admin/genre', name: 'app_admin_genre')]
    public function index(GenreRepository $genreRepository): Response
    {

        $genres = $genreRepository->findAllGenresOrderedBy(); // Fonction 'DQL' custom définie dans GenreRepository pour faire la même chose que findBy()//findBy([], ['nom'=> 'asc']); // lister tous mes genres, le findBy permet de lister et CLASSER les nom de genre par ordre alphabétique

        return $this->render('admin/genre/index.html.twig', [
            'genres' => $genres, // passage à la vue représentée par la variable 'genres' de tous mes genres stockés dans la variable $genres
        ]);
    }

    #[Route('/admin/genre/ajouter', name: 'app_admin_genre_ajouter')] // Je peux préfixer les routes car elles commencent à se répeter mais je ne vais pas le faire
    #[Route('/admin/genre/modifier/{id}', name: 'app_admin_genre_modifier')]

    public function editer (Request $request, EntityManagerInterface $entityManager, GenreRepository $genreRepository, ?int $id = null): Response
    {

        if($request->attributes->get('_route') == 'app_admin_genre_ajouter') {
            //Si le ci dessus : nom de la route = app admin genre ajouter alr : créer une new instance de l'entité Genre
            $genre = new Genre(); // Nouvelle entité Genre vide
        }else {
            //récuperer le genre existant que je modifie en base
            $genre = $genreRepository->find($id); // Ceci est donc une Entité genre extraite de la BDD donc pas vide
        }

     ;
        $form = $this->createForm(GenreType::class, $genre); // Création d'un formulire. L'entité bindée, liée à mon form c'est $genre
        $form->handleRequest($request); // récupère le form qui sera POST

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($genre);
            $entityManager->flush(); // Envoie le forumalire dans la BDD

          // Gestion du MESSAGE de CONFIRMATION:
            if($request->attributes->get('_route') == 'app_admin_genre_ajouter') {
                $this->addFlash(
                    'success',
                    message: "Le nouveau genre a bien été ajouté !"
                );
            } else if ($request->attributes->get('_route') == 'app_admin_genre_modifier'){
                $this->addFlash(
                    'success',
                    message: "Le genre a bien été modifié !"
                );
            }

            return $this->redirectToRoute('app_admin_genre'); // je redirige
        }
        return $this->render('admin/genre/editer.html.twig', [
            'form' => $form, // passage à la vue représentée par la variable 'form' de mon formulaire stockés dans la variable $form
        ]);
    }

    #[Route('/admin/genre/supprimer/{id}', name: 'app_admin_genre_supprimer')]
    public function supprimer(GenreRepository $genreRepository, ?int $id, EntityManagerInterface $entityManager ): Response
    {

        $genres = $genreRepository->find($id);
        $entityManager->remove($genres);
        $entityManager->flush();


        $this->addFlash(
            'success',
            message: "Le genre a bien été supprimé !"
        );

        return $this->redirectToRoute('app_admin_genre'); // je redirige


    }
}
