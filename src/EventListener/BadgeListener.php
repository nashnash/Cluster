<?php


namespace App\EventListener;


use App\Entity\Badge;
use App\Entity\Conversation;
use App\Entity\User;
use App\Repository\BadgeRepository;
use App\Repository\ConversationRepository;
use DateTime;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Security;

/**
 * Class BadgeListener
 * @package App\EventListener
 */
class BadgeListener
{

    /**
     * Array contains slug (in db) => name of function in service
     *
     * @var array|string[]
     */
    private array $badges_func = [
        "sort-de-ta-taniere" => "checkSortDeTaTaniere",
        "sociable" => "checkSociableUser",
        "ermite-out" => "checkHermit"
    ];

    private array $badges_needed = [];

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var Security
     */
    private Security $security;
    /**
     * @var Session
     */
    private Session $session;

    /**
     * BadgeListener constructor.
     * @param EntityManagerInterface $em
     * @param Security $security
     * @param SessionInterface $session
     */
    public function __construct(EntityManagerInterface $em, Security $security, SessionInterface $session)
    {
        $this->em = $em;
        $this->security = $security;
        $this->session = $session;
    }

    public function check()
    {
        if (!$this->security->getUser()) return;

        /** @var Badge[] $badgeNotYetObtains */
        $badgeNotYetObtains = $this->getBadgesNotObtainedByCurrentUser();
        foreach ($badgeNotYetObtains as $badge) {
            $this->{$this->badges_func[$badge->getSlug()]}();
        }
    }

    /**
     * @return mixed
     */
    public function checkSortDeTaTaniere()
    {
        /** @var User $user */
        $user = $this->security->getUser();

        /** @var ConversationRepository $conversationRepository */
        $conversationRepository = $this->em->getRepository(Conversation::class);

        $conversations = $conversationRepository->findBy(['owner' => $user]);
        foreach ($conversations as $conversation) {
            if ($conversation->getParticipants()->count() >= 3) {
                $this->addBadge();
            }
        }
    }

    public function checkSociableUser()
    {
        $user = $this->security->getUser();
        if ($user->getFriends()->count() >= 1) {
            $this->addBadge();
        }
    }

    public function checkHermit()
    {
        /** @var User $user */
        $user = $this->security->getUser();
        foreach ($user->getEvents() as $event) {
            if ($event->getDateEnd() <= new DateTime()) {
                $this->addBadge();
            }
        }
    }

    private function addBadge()
    {
        $user = $this->security->getUser();
        /** @var BadgeRepository $repository */
        $repository = $this->em->getRepository(Badge::class);
        $badge = $repository->findOneBy(['slug' => array_search(debug_backtrace()[1]['function'], $this->badges_func)]);
        $user->addBadge($badge);
        $this->em->flush();
        return $this->session->getFlashBag()->add('success', 'Vous venez de débloquer le badge "' . $badge->getName() . ' !"');

    }

    /**
     * @return Collection|Badge[]
     */
    private function getBadgesNotObtainedByCurrentUser()
    {
        /** @var BadgeRepository $repository */
        $repository = $this->em->getRepository(Badge::class);
        return $repository->getBadgesNotAcquired($this->security->getUser());
    }
}