<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    // /**
    //  * @return Event[] Returns an array of Event objects
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
    public function findOneBySomeField($value): ?Event
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * Returns an array of professional's Event objects
     * @param $idUser
     * @return int|mixed|string
     */
    public function findEventsOfProfesional($idUser)
    {

        $em = $this->getEntityManager();
        $query = $em->createQuery("
                SELECT e, u, r FROM App\Entity\Event e
                JOIN e.user u
                JOIN e.restrictions r
                WHERE e.user = :idUser");
        $query->setParameter('idUser', $idUser);
        return $query->getResult();

    }

    /**
     * Returns current Event
     * @param $idUser
     * @param $idEvent
     * @return int|mixed|string
     */
    public function findCurrentEvent($idUser, $idEvent)
    {

        $em = $this->getEntityManager();
        $query = $em->createQuery("
                SELECT e, u, r FROM App\Entity\Event e
                JOIN e.user u
                JOIN e.restrictions r
                WHERE e.user = :idUser
                AND e.id = :idEvent");
        $query->setParameter('idUser', $idUser)
            ->setParameter('idEvent', $idEvent);
        return $query->getResult();

    }
}
