<?php
/**
 * Event Controller
 */
namespace AppBundle\Controller;

use AppBundle\Entity\Event;
use AppBundle\Repository\EventRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Welp\IcalBundle\Response\CalendarResponse;

/**
 * Event controller.
 *
 * @Route("event")
 */
class EventController extends BaseController
{
    /**
     * Lists all events.
     *
     * @Route(
     *     "/",
     *     defaults={"page": 1},
     *     name="event_index",
     * )
     * @Route(
     *     "/page/{page}",
     *     requirements={"page": "[1-9]\d*"},
     *     name="event_index_paginated",
     * )
     * @Method("GET")
     * @param int $page Page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction($page)
    {
        $repository = $this->get("app.repository.event");
        $events = $repository->findAllPaginated($this->getUserProfile(), $page);

        return $this->render('event/index.html.twig', array(
            'events' => $events,
        ));
    }

    /**
     * Generate calendar event ICAL for welpAction
     * @Route("/ical", name="app_ical")
     * @return CalendarResponse
     */
    public function icalAction()
    {
        $calendar = $this->get('app.service.iCalService')->createICal($this->getUserProfile());

        $headers = array();
        $calendarResponse = new CalendarResponse($calendar, 200, $headers);

        return $calendarResponse;
    }

    /**
     * Lists events from day.
     *
     * @Route("/day/{date}", name="day_event")
     * @Method("GET")
     *
     * parse date: 10%20september%202000 or now
     * @param \DateTime $date Date
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function dayIndexAction($date)
    {
        $date = new \DateTime($date);
        /** @var EventRepository $eventRep */
        $eventRep = $this->get("app.repository.event");
        $profile = $this->getUserProfile();
        $events = $eventRep->findTodayEvents($profile, $date);

        return $this->render('event/day.html.twig', array(
            'events' => $events,
        ));
    }

    /**
     * Lists events from day.
     *
     * @Route("/month/{month}/{year}", name="month_event")
     * @Method("GET")
     *
     * parse date: 10%20september%202000 or now
     * @param int $month Month
     * @param int $year  Year
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function monthIndexAction($month, $year)
    {
        /** @var EventRepository $eventRep */
        $eventRep = $this->get("app.repository.event");
        $profile = $this->getUserProfile();
        $events = $eventRep->findMonthEvents($profile, $month, $year);

        return $this->render('event/month.html.twig', array(
            'events' => $events,
        ));
    }

    /**
     * Creates a new event entity.
     *
     * @Route("/new", name="event_new")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $event = new Event();
        $event->setProfile($this->getUserProfile());
        $form = $this->createForm('AppBundle\Form\EventType', $event, ['owner' => $this->getUserProfile()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($event);
            $em->flush();
            $this->addFlash('success', 'form.event_new.success');

            return $this->redirectToRoute('event_show', array('id' => $event->getId()));
        }

        return $this->render('event/new.html.twig', array(
            'event' => $event,
            'form' => $form->createView(),
        ));
    }

    /**
     * Show event
     *
     * @Route("/{id}", name="event_show")
     * @Method("GET")
     * @param Event $event
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(Event $event)
    {
        $this->checkAccessPermission($event);
        $deleteForm = $this->createDeleteForm($event);

        return $this->render('event/show.html.twig', array(
            'event' => $event,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edit event
     *
     * @Route("/{id}/edit", name="event_edit")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param Event   $event
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, Event $event)
    {
        $this->checkAccessPermission($event);
        $editForm = $this->createForm('AppBundle\Form\EventType', $event, ['owner' => $this->getUserProfile()]);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'form.event_edit.success');

            return $this->redirectToRoute('event_edit', array('id' => $event->getId()));
        }

        return $this->render('event/edit.html.twig', array(
            'event' => $event,
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Delete event.
     *
     * @Route("/{id}", name="event_delete")
     * @Method("DELETE")
     * @param Request $request
     * @param Event   $event
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, Event $event)
    {
        $this->checkAccessPermission($event);
        $form = $this->createDeleteForm($event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $this->addFlash('success', 'form.event_delete.success');
            $em->remove($event);
            $em->flush();
        }

        return $this->redirectToRoute('event_index');
    }

    /**
     * Create form to delete event.
     *
     * @param Event $event
     * @return \Symfony\Component\Form\Form
     */
    private function createDeleteForm(Event $event)
    {
        return $this->createFormBuilder(null, ['attr' => ['id' => 'delete_form']])
            ->setAction($this->generateUrl('event_delete', array('id' => $event->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
