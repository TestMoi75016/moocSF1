<?php

namespace App\Controller;

use App\Repository\JeuRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class JeuController extends AbstractController
{
    #[Route('/jeu', name: 'app_jeu')] //'/jeu' = URL de ma route http://127.0.0.1:8000/jeu
    public function index(JeuRepository $jeuRepository): Response // Un Controller de Symfony DOIT retourner une réponse sous forme d'objet.
    {
        $jeux = $jeuRepository->findAll(); // Stock tous mes jeux dans $jeux
        // dd($jeux); //Définition : dd() signifie "dump and die" (affiche le contenu d'une variable ou d'un objet, et arrête immédiatement l'exécution du script. C'est une fonction très pratique fournie par Symfony (et Laravel) pour déboguer vos applications.
        return $this->render('jeu/index.html.twig', [ // retourne ma vue index.html.twing dans 'templates' grace à la méthode render()
            'jeux' => $jeux // ICI Le [] est un tableau associatif contenant les données que vous souhaitez transmettre à la vue. Les clés du tableau deviendront des variables accessibles dans le fichier Twig.
        ]);
    }
}
