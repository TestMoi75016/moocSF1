<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class JeuController extends AbstractController
{
    #[Route('/jeu', name: 'app_jeu')] //'/jeu' = URL de ma route http://127.0.0.1:8000/jeu
    public function index(): Response // Un Controller de Symfony DOIT retourner une réponse sous forme d'objet.
    {
        return $this->render('jeu/index.html.twig', [ // retourne ma vue index.html.twing dans 'templates' grace à la méthode render()
            'controller_name' => 'JeuController', // ICI Le [] est un tableau associatif contenant les données que vous souhaitez transmettre à la vue. Les clés du tableau deviendront des variables accessibles dans le fichier Twig.
        ]);
    }
    #[Route('/jeu/test', name: 'app_jeu_test')] //'/jeu/test' = URL de ma route http://127.0.0.1:8000/jeu/test
    public function test(): Response // Cette fois la fonction est 'test' du même nom que l'url
    {
        return $this->render('jeu/test.html.twig'); // On rend la nouvelle vue
    }
}
