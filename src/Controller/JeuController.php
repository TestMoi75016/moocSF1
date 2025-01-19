<?php

namespace App\Controller;

use App\Repository\JeuRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/jeu', name: 'app_jeu')]
class JeuController extends AbstractController
{
    #[Route('/', name: '')] //'/jeu' = URL de ma route http://127.0.0.1:8000/jeu
    public function index(JeuRepository $jeuRepository): Response // Un Controller de Symfony DOIT retourner une réponse sous forme d'objet.
    {
        $jeux = $jeuRepository->findJeuxPourAfficherEnNombreLimited(6); //Affichera 6 jeux max. Méthode custom de jeuRepository        // findAll(); // Stock tous mes jeux dans $jeux
        // dd($jeux); //Définition : dd() signifie "dump and die" (affiche le contenu d'une variable ou d'un objet, et arrête immédiatement l'exécution du script. C'est une fonction très pratique fournie par Symfony (et Laravel) pour déboguer vos applications.
        return $this->render('jeu/index.html.twig', [ // retourne ma vue index.html.twing dans 'templates' grace à la méthode render()
            'jeux' => $jeux // ICI Le [] est un tableau associatif contenant les données que vous souhaitez transmettre à la vue. Les clés du tableau deviendront des variables accessibles dans le fichier Twig.
        ]);
    }
    #[Route('/voir/{id}', name: '_voir')] //'/jeu' = URL de ma route 'voir'
    public function voir(JeuRepository $jeuRepository, ?int $id): Response // Un Controller de Symfony DOIT retourner une réponse sous forme d'objet.
    {
        $jeu = $jeuRepository->find($id); // On récupère le jeu par son ID
        return $this->render('jeu/voir.html.twig', [ // retourne ma vue index.html.twing dans 'templates' grace à la méthode render()
            'jeu' => $jeu // ICI Le [] est un tableau associatif contenant les données que vous souhaitez transmettre à la vue. Les clés du tableau deviendront des variables accessibles dans le fichier Twig.
        ]);
    }
    #[Route('/liste', name: '_liste')] // URL de ma route 'liste'
    public function liste(JeuRepository $jeuRepository, ?int $id): Response // Un Controller de Symfony DOIT retourner une réponse sous forme d'objet.
    {
        $jeux = $jeuRepository->findBy([], ['nom' => 'ASC']); // On récupère les jeux triés par nom ordre alphabétique
        return $this->render('jeu/liste.html.twig', [ // retourne ma vue index.html.twing dans 'templates' grace à la méthode render()
            'jeux' => $jeux // Ici j'envoie mes jeux à la vue
        ]);
    }

}


