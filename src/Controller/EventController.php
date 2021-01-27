<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\User;
use App\Form\EventType;
use App\Repository\EventRepository;
use App\Repository\UserRepository;
use Eluceo\iCal\Component\Calendar;
use Eluceo\iCal\Property\Event\Geo;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * @Route("/dashboard/events")
 */
class EventController extends AbstractController
{
    /**
     * @Route("/", name="event_index", methods={"GET"})
     * @param EventRepository $eventRepository
     * @return Response
     */
    public function index(EventRepository $eventRepository): Response
    {
        return $this->render('event/index.html.twig', [
            'events' => $eventRepository->findNext10DaysEvents()
        ]);
    }

    /**
     * @Route("/new", name="event_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $event->setUser($this->getUser());
            $event->setStatus("Open");

            $entityManager->persist($event);
            $entityManager->flush();

            return $this->redirectToRoute('event_index');
        }

        return $this->render('event/new.html.twig', [
            'event' => $event,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="event_show", methods={"GET"})
     * @param Event $event
     * @return Response
     */
    public function show(Event $event): Response
    {
        return $this->render('event/show.html.twig', [
            'event' => $event
        ]);
    }

    /**
     * @Route("/{id}/edit", name="event_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Event $event
     * @return Response
     */
    public function edit(Request $request, Event $event): Response
    {
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('event_index');
        }

        return $this->render('event/edit.html.twig', [
            'event' => $event,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="event_delete", methods={"DELETE"})
     * @param Request $request
     * @param Event $event
     * @return Response
     */
    public function delete(Request $request, Event $event): Response
    {
        if ($this->isCsrfTokenValid('delete' . $event->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($event);
            $entityManager->flush();
        }

        return $this->redirectToRoute('event_index');
    }

    /**
     * @Route("/reserve/{id}", name="event_reserve", methods={"GET"})
     * @param Event $event
     * @return RedirectResponse
     */
    public function reserve(Event $event): RedirectResponse
    {
        $event->addParticipant($this->getUser());
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();
        return $this->redirectToRoute('event_index');
    }

    /**
     * @Route("/desiste/{id}", name="event_desiste")
     * @param Event $event
     * @return RedirectResponse
     */
    public function desiste(Event $event): RedirectResponse
    {
        $event->removeParticipant($this->getUser());
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();
        return $this->redirectToRoute('event_index');
    }

    /**
     * @Route("/remove_reservation/{id}/{id_user}", name="event_remove_participant")
     * @param UserRepository $userRepository
     * @param Event $event
     * @param int|null $id_user
     * @return RedirectResponse
     */
    public function removeReservation(UserRepository $userRepository, Event $event, ?int $id_user): RedirectResponse
    {
        $user = $userRepository->find($id_user);
        $event->removeParticipant($user);
        $em = $this->getDoctrine()->getManager();
        $em->flush();
        return $this->redirectToRoute('event_show', ['id' => $event->getId()]);
    }

    /**
     * @Route("/events_past",methods={"GET"},name="events_pasts")
     * @param EventRepository $eventRepository
     * @return Response
     */
    public function eventsInPast(EventRepository $eventRepository): Response
    {

        return $this->render('event/pasts.html.twig', [
            'events' => $eventRepository->getPastEvents($this->getUser())
        ]);
    }

    /**
     * @Route("/add_calendar/{id}",name="add_calendar", methods={"GET"})
     * @param Event $event
     * @param HttpClientInterface $client
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function addCalendar(Event $event, HttpClientInterface $client)
    {
        $vCalendar = new Calendar('13123');
        $iCalEvent = new \Eluceo\iCal\Component\Event();

        // Get lat & lng

        $response = $client->request(
            'GET',
            'https://nominatim.openstreetmap.org/search/1%20rue%20raymond%20du%20temple%20Vincennes?format=json&addressdetails=1&limit=1&polygon_svg=1'
        );

        $geoResponse = json_decode($response->getContent())[0];

        $iCalEvent->setSummary($event->getName());
        $iCalEvent->setGeoLocation(new Geo($geoResponse->lat, $geoResponse->lon));
        $iCalEvent->setDescription($event->getDescription());
        $iCalEvent->setDtStart($event->getDateStart());
        $iCalEvent->setDtEnd($event->getDateEnd());
        $vCalendar->addComponent($iCalEvent);

        header('Content-Type: text/calendar; charset=utf-8');
        header('Content-Disposition: inline; filename="cal.ics"');
        echo($vCalendar->render());
        exit;
    }
}
