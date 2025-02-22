<?php

namespace App\DataFixtures;

use App\Entity\Genre;
use App\Entity\Jeu;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker; // RAJOUT A LA MAIN pour utiliser Faker que je possède dans mon dossier vender en tant que dépendance 
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $userPasswordHasher; // Déclaration explicite de la propriété $userPasswordHasher de type "UserPasswordHasherInterface"


    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }




    public function load(ObjectManager $manager): void // grâce ) ObjectManager on inject un fake utilisateur dans la BDD
    {

        $faker = Faker\Factory::create();
        $genres = ["Stratégie", "Familiale", "Ambiance", "Cooperatif"]; // Car Faker ne connait pas de "genre de jeu" en Français d'après la doc

        for ($i = 0; $i < 10; $i++) { // Je mets tout dans une boucle FOR afin d'avoir 10 "jeu" dans ma table Jeu dans ma DB
            $genre= new Genre();
            $genre->setNom($genres[$i % count($genres)]);
            $genre->setPopularite($faker->numberBetween(0,10)); // génère un nombre random entre 0 et 10
            $genre->setCouleur($faker->hexColor); // genere une couleur aléatoire
            $manager->persist($genre); // persistement , puis le flush tout en bas fera la save

            $jeu = new Jeu(); // Nouvelle instance de l'entité Jeu (D'ou le use App\Entity\Jeu;)
            $jeu->setNom($faker->streetName); // Ensuite je "set" chaque "champ" | $faker->streetName() : il n'ya pas de nom de jeu dans fakerds la doc , donc je choisi d'utiliser streetNAme pour faire semblant d'avoir des noms qui font nom de jeu
            $jeu->setDateSortie($faker->dateTime);
            $jeu->setGenre($genre); // ($genre[rand(0,3)]) "rand" pour "random". va choisir une valeur à un index entre 0 et 3 du tableau $genre aléatoirement
            $jeu->setDescription($faker->text(50));
            $manager->persist($jeu); // Ensuite je persiste mon entité (attention persist() n'écrit rien dans la DB)
        }

        $user = new User(); // Nouvelle instance de l'entité User
        $user->setEmail("user@gmail.com");
        $user->setNom($faker->name);
        $user->setPrenom($faker->firstname);
        $user->setPassword($this->userPasswordHasher->hashPassword($user, "123")); // même si j'ai mis une securité pour un mdp à partir de 8 caractères, en tant que dev dans les fixtures je peux mettre n'imp quel mdp
        $user->setRoles(["ROLE_USER"]);
        $manager->persist($user); // Ensuite je persiste mon entité (attention persist() n'écrit rien dans la DB)$user = new User(); // Nouvelle instance de l'entité User

        $user = new User();
        $user->setEmail("admin@gmail.com");
        $user->setNom($faker->name);
        $user->setPrenom($faker->firstname);
        $user->setPassword($this->userPasswordHasher->hashPassword($user, "123"));
        $user->setRoles(["ROLE_ADMIN"]);
        $manager->persist($user); // Ensuite je persiste mon entité (attention persist() n'écrit rien dans la DB)


        $manager->flush(); // prend toutes les Entités qui ont subit le persist() (ici "$jeu") et les SAUVEGARDE dans la DB (si elles n'existent pas déjà encore dans la DB)
        //Si les Entité existent deja dans la DB : flush() va faire un update
    }
}
