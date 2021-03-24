<?php

namespace App\Repository;

use App\Entity\Bid;
use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Bid|null find($id, $lockMode = null, $lockVersion = null)
 * @method Bid|null findOneBy(array $criteria, array $orderBy = null)
 * @method Bid[]    findAll()
 * @method Bid[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BidRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bid::class);
    }


    /**
     * Trouve le l'enchère courante
     * @param Event $event
     * @return Bid|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findCurrentBid(Event $event): ?Bid
    {
        return $this->createQueryBuilder('b')
            ->where('b.event = :event')
            ->setParameter('event', $event->getId())
            ->getQuery()
            ->getOneOrNullResult();
    }


}
