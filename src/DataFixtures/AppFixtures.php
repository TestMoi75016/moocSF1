<?php

namespace App\DataFixtures;

use App\Entity\Jeu;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker; // RAJOUT A LA MAIN pour utilisé Faker que je possède dans mon dossier vender en tant que dépendance

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $faker = Faker\Factory::create();
        $genre = ["Stratégie", "Familiale", "Ambiance", "Cooperatif"]; // Car Faker ne connait pas de "genre de jeu" en Français d'après la doc

        for ($i = 0; $i < 10; $i++) { // Je mets tout dans une boucle FOR afin d'avoir 10 "jeu" dans ma table Jeu dans ma DB
            $jeu = new Jeu(); // Nouvelle instance de l'entité Jeu (D'ou le use App\Entity\Jeu;)
            $jeu->setNom($faker->streetName); // Ensuite je "set" chaque "champ" | $faker->streetName() : il n'ya pas de nom de jeu dans fakerds la doc , donc je choisi d'utiliser streetNAme pour faire semblant d'avoir des noms qui font nom de jeu
            $jeu->setDateSortie($faker->dateTime);
            $jeu->setGenre($genre[rand(0, 3)]); // ($genre[rand(0,3)]) "rand" pour "random". va choisir une valeur à un index entre 0 et 3 du tableau $genre aléatoirement
            $jeu->setDescription($faker->text(50));
            $manager->persist($jeu); // Ensuite je persiste mon entité (attention persist() n'écrit rien dans la DB)
        }
        $manager->flush(); // prend toutes les Entités qui ont subit le persist() (ici "$jeu") et les SAUVEGARDE dans la DB (si elles n'existent pas déjà encore dans la DB)
        //Si les Entité existent deja dans la DB : flush() va faire un update
    }
}
