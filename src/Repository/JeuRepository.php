<?php

namespace App\Repository;

use App\Entity\Jeu;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Jeu>
 * PERMET D'ACCEDER AUX DONNEES
 *
 * @method Jeu|null find($id, $lockMode = null, $lockVersion = null)
 * @method Jeu|null findOneBy(array $criteria, array $orderBy = null)
 * @method Jeu[]    findAll()
 * @method Jeu[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JeuRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Jeu::class);
    }

    /** Fonction CUSTOM où écrire du dql (doctrine SQL) pr AFFICHER un nombre LIMITER de jeu
     * @return Jeu[] Returns an array of Jeu objects
     */
    public function findJeuxPourAfficherEnNombreLimited(int $jeuxMax): array // APPEL de cette méthode dans JeuController
    {  // Doctrine génère automatiquement le "SELECT *"
        return $this->createQueryBuilder('j') // ici ça équivaut à "FROM jeu j". Spécifie que vous travaillez sur la table jeu et lui donne l'alias j.
            ->orderBy('j.id', 'ASC') // "ORDER BY j.id ASC" : Trie les résultats par la colonne id dans l'ordre croissant
            ->setMaxResults($jeuxMax) //"LIMIT :jeuxMax" Limite le nombre de résultats retournés par la requête à la valeur de :jeuxMax
            ->getQuery()
            ->getResult() // getQuery() et getResult() sont essentiels pour obtenir le resultat de la requête.
        ;
    }

    //    public function findOneBySomeField($value): ?Jeu
    //    {
    //        return $this->createQueryBuilder('j')
    //            ->andWhere('j.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
