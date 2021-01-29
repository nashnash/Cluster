<?php

namespace App\Repository;

use App\Entity\Event;
use App\Entity\User;
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
     * @return int|mixed|string
     */
    public function findNext10DaysEvents()
    {
        return $this->createQueryBuilder('e')
            ->leftJoin('e.participants', 'participants')
            ->where('DATE_DIFF(CURRENT_DATE(),e.date_start) <= 10 AND DATE_DIFF(CURRENT_DATE(),e.date_start) <= 0')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param User $user
     * @return int|mixed|string
     */
    public function getPastEvents(User $user)
    {
        return $this->createQueryBuilder('e')
            ->leftJoin('e.participants', 'participants')
            ->where('e.user = :user')
            ->orWhere('participants.id = :user')
            ->andWhere('e.date_end < CURRENT_DATE()')
            ->setParameter('user', $user->getId())
            ->getQuery()
            ->getResult();
    }

    /**
     * @param User $user
     * @return int|mixed|string
     */
    public function getActualEvents(User $user)
    {
        return $this->createQueryBuilder('e')
            ->leftJoin('e.participants', 'participants')
            ->where('e.user = :user')
            ->orWhere('participants.id = :user')
            ->andWhere('e.date_end >= CURRENT_DATE()')
            ->andWhere('e.date_start <= CURRENT_DATE()')
            ->setParameter('user', $user->getId())
            ->getQuery()
            ->getResult();
    }
}
