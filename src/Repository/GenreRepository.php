<?php

namespace App\Repository;

use App\Entity\Genre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Genre>
 */
class GenreRepository extends ServiceEntityRepository
// Les méthodes find, findAll, findBy, findOneBy, et count sont bien utilisables par cette class GenreRepository même si pas visibles dans ce fichier car elles sont 'HERITEES' de la classe parente ServiceEntityRepository, dont ce repository dépend
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Genre::class);
    }

    // Si j'ai besoin d'une fonction custom afin de récuperer mes données les find,findAll,findBy,findOneBy ne me satisfont pas je peux creer une custom 'DQL' :
    public function findAllGenresOrderedBy($order = "nom", $direction = "ASC" ) :array {
        return $this->createQueryBuilder('g')  //g pour genre
            ->orderBy('g.'.$order, $direction)
            ->getQuery()
            ->getResult();
    }
    //    /**
    //     * @return Genre[] Returns an array of Genre objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('g')
    //            ->andWhere('g.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('g.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Genre
    //    {
    //        return $this->createQueryBuilder('g')
    //            ->andWhere('g.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
