<?php

namespace App\Controller;

use App\Entity\Messages;
use App\Form\SendMessageConversationType;
use App\Repository\ConversationRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DiscussionsController
 * @package App\Controller
 */
class DiscussionsController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * DiscussionsController constructor.
     * @param EntityManagerInterface $manager
     */
    public function __construct(EntityManagerInterface $manager)
    {
        $this->em = $manager;
    }

    /**
     * @Route("/discussions/{id}", name="discussions")
     * @param ConversationRepository $conversationRepository
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function index(ConversationRepository $conversationRepository, Request $request, int $id = 0): Response
    {
        $message = new Messages();

        $formMessage = $this->createForm(SendMessageConversationType::class, $message);
        $formMessage->handleRequest($request);

        $conversations = $conversationRepository->getLastUpdated($this->getUser());

        $conversation = ($id > 0) ? $conversationRepository->find($id) : $conversations[0];

        if ($formMessage->isSubmitted() && $formMessage->isValid()) {
            $message->setConversation($conversation);
            $message->setMessage($formMessage->get('message')->getData());
            $message->setCreatedAt(new DateTime());
            $message->setUpdatedAt(new DateTime());
            $message->setUser($this->getUser());

            $this->em->persist($message);
            $this->em->flush();
            return $this->redirectToRoute("discussions",['id' => $id ?? null]);
        }

        return $this->render('discussions/index.html.twig', [
            'conversations' => $conversations,
            'activeConversation' => $conversation,
            'formMessage' => $formMessage->createView()
        ]);
    }
}
