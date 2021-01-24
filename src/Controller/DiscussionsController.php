<?php

namespace App\Controller;

use App\Repository\ConversationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DiscussionsController extends AbstractController
{
    /**
     * @Route("/discussions", name="discussions")
     * @param ConversationRepository $conversationRepository
     * @return Response
     */
    public function index(ConversationRepository $conversationRepository): Response
    {
        setlocale(LC_TIME, "fr_FR");
        return $this->render('discussions/index.html.twig', [
            'conversations' => array_reverse($this->getUser()->getConversations()->toArray()),
            'messages' => $this->getUser()->getConversations()[count($this->getUser()->getConversations()) - 1]->getMessages()
        ]);
    }
}
