<?php

namespace App\Controller;

use App\Entity\Event;
use App\Repository\EventRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AllEventsController extends AbstractController
{
    /**
     * @Route("/all/events", name="all_events")
     */
    public function index(EventRepository  $eventRepository, PaginatorInterface $paginator,Request $request): Response
    {
        $donnees = $eventRepository->getCurrentActiveEvents();
        $events = $paginator->paginate(
            $donnees,
            $request->query->getInt('page', 1),
            7// Nombre de résultats par page
        );
        return $this->render('all_events/index.html.twig', [
            'events' => $events
        ]);
    }

    /**
     * @Route("/details/event/{slug}", name="details_event")
     */
    public function show(Event $event): Response
    {
        return $this->render('all_events/details_event.html.twig', [
            'event' => $event,
        ]);
    }
}
