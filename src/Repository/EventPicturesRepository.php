<?php

namespace App\Repository;

use App\Entity\EventPictures;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EventPictures|null find($id, $lockMode = null, $lockVersion = null)
 * @method EventPictures|null findOneBy(array $criteria, array $orderBy = null)
 * @method EventPictures[]    findAll()
 * @method EventPictures[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventPicturesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EventPictures::class);
    }

    // /**
    //  * @return EventPictures[] Returns an array of EventPictures objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EventPictures
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
