<?php

namespace App\Controller;

use App\Form\UserPasswordType;
use App\Service\MailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
#[Route('/profil', name: 'app_profil')]

class ProfilController extends AbstractController
{
    #[Route('/', name: '')]
    public function index(): Response
    {
        return $this->render('profil/index.html.twig', [
            'controller_name' => 'ProfilController',
        ]);
    }
    #[Route('/changepassword', name: '_changepassword')]
    public function changepassword(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager , MailService $mailService): Response
    {
        // Un formulaire est toujours lié à une Entité qu'on cherche à modifier
        $user = $this -> getUser();
        //dd($user); // montre toutes infos de l'utilisateur, donc getUser() renvoie bien toutes infos de l'user .
        $form = $this ->createForm(UserPasswordType::class, $user);

        $form -> handleRequest($request); //Récupérer tous les champs qui seront postés
    if ($form -> isSubmitted() && $form -> isValid()) {

        // Si le mdp actuel n'est pas bon
        if(!$userPasswordHasher -> isPasswordValid($user, $form->get('currentPassword')->getData())) {

            $this->addFlash(
                'danger',
                message: "Votre mot de passe est incorrect !"
            );
            return $this->redirectToRoute('app_profil_changepassword'); // je redirige
        }



        $user->setPassword(
            $userPasswordHasher ->hashPassword(
                $user,
                $form->get('password')->getData() // Bien que le champ password ne soit PLUS mappé à l'entité, Symfony  permet de récupérer la valeur soumise via la méthode getData() même si à cause de 'mapped: false"  Symfony ne doit plus MAJ automatiquement les propriété de l'entité, getData() permet de récupérer ces données, et la suite du code va persister ces données
        ));

        // dd($user);

         $entityManager->persist($user);
         $entityManager->flush(); // entityManager avec les méthodes persist() et flush() permet d'enregistrer mon User dans la DBB (enfin ici la modif du mdp)

        //dd("Jenvoie un email");
        $mailService->sendMail('mike@hotmail.com', 'Changement mot de passe ! ', 'Ce mail vous est confirmé votre changement de mot de passe');


        $this->addFlash(
            'success',
            message: 'Votre mot de passe a bien été modifié'
        ); // messsage flash pour dire à l'user que l'opératiion de modif du mdp a été effectué (On avait déjà tout setup dans base.html.twig pour les flashMessage)
        return $this->redirectToRoute('app_profil'); // je redirige

    }
        return $this->render('profil/change_password.html.twig', [
            'form' => $form]);
    }
}
