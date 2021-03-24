<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use App\Entity\Bid;
use App\Form\BidType;
use App\Repository\BidRepository;
use App\Repository\EventRepository;
use App\Repository\UserRepository;
use Eluceo\iCal\Component\Calendar;
use Eluceo\iCal\Component\Event as ICalEvent;
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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


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
            'events' => $eventRepository->findNext10DaysEvents(),
            'eventsTopList' => $eventRepository->getEventsProByTopList()
        ]);
    }

    /**
     * @Route("/new", name="event_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        //throw $this->createAccessDeniedException("Vous ne pouvez pas accéder");

        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $event->setUser($this->getUser());
            $event->setStatus("Pending");

            $entityManager->persist($event);
            $entityManager->flush();

            $this->addFlash('success', 'Event Created');

            return $this->redirectToRoute('event_index');
        }

        return $this->render('event/new.html.twig', [
            'event' => $event,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/past",name="past_event")
     * @param EventRepository $eventRepository
     * @return Response
     */
    public function pastEvents(EventRepository $eventRepository): Response
    {

        return $this->render('event/pasts.html.twig', [
            'events' => $eventRepository->getPastEvents($this->getUser()),
        ]);
    }

    /**
     * @Route("/actual_future",name="actual_future_event")
     * @param EventRepository $eventRepository
     * @return Response
     */
    public function actualFutureEvents(EventRepository $eventRepository): Response
    {

        //dd($eventRepository->getActualEvents($this->getUser()));

        return $this->render('event/actual_future.html.twig', [
            'events' => $eventRepository->getActualEtFutureEventsByPro($this->getUser()),
           // 'events' => $eventRepository->findNext10DaysEventsByPro($this->getUser())

        ]);
    }

    /**
     * @Route("/{id}", name="event_show", methods={"GET"})
     * @param Event $event
     * @return Response
     */
    public function show(Event $event): Response
    {


        $numberOfVisits = $event->getNumberOfVisits();
        //if($numberOfVisits == 0){

            $event->setNumberOfVisits($numberOfVisits+1);
            $this->getDoctrine()->getManager()->flush();

       // }

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
     * @Route("/add_calendar/{id}",name="add_calendar", methods={"GET"})
     * @param Event $event
     * @param HttpClientInterface $client
     */
    public function addCalendar(Event $event, HttpClientInterface $client)
    {
        $vCalendar = new Calendar('13123');
        $iCalEvent = new ICalEvent();

        $iCalEvent->setSummary($event->getName());
        $iCalEvent->setDescription($event->getDescription());
        $iCalEvent->setDtStart($event->getDateStart());
        $iCalEvent->setDtEnd($event->getDateEnd());
        $vCalendar->addComponent($iCalEvent);

        header('Content-Type: text/calendar; charset=utf-8');
        header('Content-Disposition: inline; filename="cal.ics"');
        echo($vCalendar->render());
        exit;
    }

    /**
     * @Route("/stats/{id}",name="event_stats", methods={"GET"})
     * @param Request $request
     * @param Event $event
     * @param EventRepository $eventRepository
     * @return Response
     */
    public function stats(Request $request, Event $event, EventRepository $eventRepository) :Response
    {



        $nbReservation = count($eventRepository->getEventStats($event)->getParticipants());

        $nbPlaceRestante = $eventRepository->getEventStats($event)->getNbParticipants();
        $nbPlaceTotale = $nbPlaceRestante + $nbReservation ;

        // Moyenne = nbParticipants / NbParticipants totale * 100
        $moyenneParticipation = intval($nbReservation /$nbPlaceTotale * 100);

        $nbPromotion = 0;
        foreach ( $eventRepository->getEventStats($event)->getBids() as $promotion )
            $nbPromotion = $promotion->getNbPromotion();

        $prixEvent = $eventRepository->getEventStats($event)->getPrice();

        $gainsAttenduEvent = 0;

        $percentBeneficeEvent = 0;
        $percentPerteEvent = 0;

        $gainsObtenuEvent = $percentBeneficeEvent * $gainsAttenduEvent / 100;
        $gainsPerduEvent= $percentPerteEvent * $gainsAttenduEvent / 100;

        $nbVisitsByEvent = $eventRepository->getEventStats($event)->getNumberOfVisits();

        if($prixEvent > 0){

            $gainsAttenduEvent = $prixEvent * $nbPlaceTotale ;

            $percentBeneficeEvent = intval($nbReservation * $prixEvent/$gainsAttenduEvent*100);
            $percentPerteEvent = 100 - $percentBeneficeEvent;

            $gainsObtenuEvent = $percentBeneficeEvent * $gainsAttenduEvent / 100;
            $gainsPerduEvent= $percentPerteEvent * $gainsAttenduEvent / 100;
        }

        return $this->render('stats/events.html.twig', [
            'nbReservation' => json_encode($nbReservation),
            'MoyenneParticipation' => json_encode($moyenneParticipation),
            'nbPromotion' => json_encode($nbPromotion),
            'percentBeneficeEvent' => json_encode($percentBeneficeEvent),
            'percentPerteEvent' => json_encode($percentPerteEvent),
            'gainsAttenduEvent' => json_encode($gainsAttenduEvent),
            'gainsObetnuEvent' => json_encode($gainsObtenuEvent),
            'gainsPerduEvent' => json_encode($gainsPerduEvent),
            'nbVisits' => json_encode($nbVisitsByEvent)
        ]);
    }

    /**
     * @Route("/promote/{id}", name="event_new_promote", methods={"GET","POST"})
     * @param Request $request
     * @param Event $event
     * @param BidRepository $bidRepository
     * @return Response
     */
    public function promoteNewEvent(Request $request, Event $event, BidRepository $bidRepository): Response
    {
        $bid = new Bid();

        $form = $this->createForm(BidType::class, $bid);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $nbPromotion = 0;

            $bid->setProfessional($this->getUser())
                ->setNbPromotion($nbPromotion + 1)
                ->setEvent($event)
                ->setCreatedAt(new \DateTime());
            // $currentBid->setCapital($form->getData()->getCapital());
            $entityManager->persist($bid);
            $entityManager->flush();

            return $this->redirectToRoute('event_index');
        }

        return $this->render('event/promote.html.twig', [
            'bid' => $bid,
            'form' => $form->createView(),
        ]);
    }

/**
 * @Route("/promote/{id}/edit", name="event_edit_promote", methods={"GET","POST"})
 * @param Request $request
 * @param Event $event
 * @param BidRepository $bidRepository
 * @return Response
 */
public function promoteEditEvent(Request $request, Event $event, BidRepository $bidRepository): Response
{


    $bid = $bidRepository->findCurrentBid($event);

    $form = $this->createForm(BidType::class, $bid);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager = $this->getDoctrine()->getManager();

        $nbPromotion = $bid->getNbPromotion() + 1 ;
        $bid->setNbPromotion($nbPromotion);

        $bid->setUpdatedAt(new \DateTime());
        $entityManager->flush();

        return $this->redirectToRoute('event_index');
    }

    return $this->render('event/promote.html.twig', [
        'bid' => $bid,
        'form' => $form->createView(),
    ]);

    }

}
