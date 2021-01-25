<?php

namespace App\Repository;

use App\Entity\Conversation;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Conversation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Conversation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Conversation[]    findAll()
 * @method Conversation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConversationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Conversation::class);
    }

    /**
     * @param User $user
     * @return array|int|string
     */
    public function getLastUpdated(User $user)
    {
        return $this->createQueryBuilder('c')
            ->leftJoin('c.messages', 'messages')
            ->leftJoin('c.participants', 'participants')
            ->where('participants.email = :email')
            ->addOrderBy('c.created_at','DESC')
            ->addOrderBy('messages.updated_at','DESC')
            ->setParameter('email', $user->getEmail())
            ->getQuery()
            ->getResult();
    }

}
