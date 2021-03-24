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
    public function findNext10DaysEventsByPro(User $user)
    {
        return $this->createQueryBuilder('e')
            ->leftJoin('e.participants', 'participants')
            ->where('e.user = :user')
            ->orWhere('participants.id = :user')
            ->setParameter("user",$user->getId())
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
    public function getActualEtFutureEventsByPro(User $user)
    {
        return $this->createQueryBuilder('e')
            ->leftJoin('e.participants', 'participants')
            ->where('e.user = :user')
            ->orWhere('participants.id = :user')
           // ->andWhere('e.date_start > CURRENT_DATE()')
            ->andWhere('e.date_end > CURRENT_DATE()')
            ->setParameter('user', $user->getId())
            ->getQuery()
            ->getResult();
    }

    /**
     * Retourne la liste des events des pro en top list de leur promotion d'event
     * @return int|mixed|string
     */
    public function getEventsProByTopList()
    {
        return $this->createQueryBuilder('e')
            ->from("App\Entity\Bid","b")
            ->from("App\Entity\User","u")
            ->where('b.event = e.id')
            ->andWhere("b.professional = u.id")
            ->orderBy('b.capital', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Obtenir les statistiques d'un évenement
     * @param Event $event
     * @return int|mixed|string
     */
    public function getEventStats(Event $event)
    {

        return $this->createQueryBuilder('e')
            ->from("App\Entity\User","u")
            ->from("App\Entity\Bid","b")
            ->leftJoin('e.participants','participants')
            ->leftJoin('e.bids', 'bids')
            ->where('e.user = u.id')
            ->andWhere("e.id = :event")
            ->setParameter('event', $event->getId())
            //->groupBy("bids.nbPromotion")
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Retourne le nombre d'événement créés par jour
     * @return int|mixed|string
     */
    public function nbCreatedEventByDay(){

        return $this->createQueryBuilder('e')
            ->select("substring(e.created_at,1,10) as dateCreation,count(e.created_at) as count")
            ->groupBy("dateCreation")
            ->getQuery()
            ->getResult();

    }
}