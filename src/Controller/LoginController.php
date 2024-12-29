<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('login/index.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }
    //ROUTE: /register et son nom est app_register :
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response  //EntityManagerInterface est le ObjectManager version Symfony : il permet d'injecter un user dans la DB
    {
        $user = new User();
        $user->setRoles(['ROLE_USER']);
        //Le formulaire
        $form = $this->createForm(UserType::class, $user); // $user = l'entité que je tente de modifier

        $form->handleRequest($request); // handleRequest permet de traiter la submission du form avec une reqûete POST. Mon entité se rempli de toutes les infos que l'user à rentrer ds le form (comme si j'avais fait un setNom() etc...)
        if ($form->isSubmitted() && $form->isValid()) {
            // dd($user); verifications des données remplis par l'user ds le form
            $user->setPassword($userPasswordHasher->hashPassword($user, $user->getPassword() /*"1234*/)); //hashage du mdp
            $entityManager->persist($user); // Ensuite je persiste mon entité (attention persist() n'écrit rien dans la DB)
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Vous êtes bien inscrit sur LocaJeu !'
            );

            return $this->redirectToRoute('app_login'); // Redirection vers la page de login une fois l'inscription validée!
        }
        //dd($user);
        return $this->render('registration/index.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
