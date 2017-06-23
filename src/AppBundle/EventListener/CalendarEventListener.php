<?php

namespace AppBundle\EventListener;

use ADesigns\CalendarBundle\Event\CalendarEvent;
use ADesigns\CalendarBundle\Entity\EventEntity;
use AppBundle\Entity\Event;
use AppBundle\Repository\EventRepository;
use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class CalendarEventListener
{
    private $user;
    private $eventRepository;
    private $router;

    public function __construct(TokenStorage $tokenStorage, EventRepository $eventRepository, Router $router)
    {
        $this->user = $tokenStorage->getToken()->getUser();
        $this->eventRepository = $eventRepository;
        $this->router = $router;
    }

    public function loadEvents(CalendarEvent $calendarEvent)
    {
        $startDate = $calendarEvent->getStartDatetime();
        $endDate = $calendarEvent->getEndDatetime();
        $profile = $this->user->getProfile();
        $userEvents = $this->eventRepository->findBetweenDates($profile, $startDate, $endDate);

        /** @var Event $userEvent */
        foreach ($userEvents as $userEvent) {
            $this->addEvent($userEvent, $calendarEvent);
        }
    }

    /**
     * @param $userEvent Event
     * @param $calendarEvent CalendarEvent
     */
    public function addEvent($userEvent, $calendarEvent)
    {
        $eventEntity = new EventEntity($userEvent->getTitle(), $userEvent->getStartDate(), $userEvent->getEndDate());
        if ($userEvent->getColor()) {
            $eventEntity->setBgColor($userEvent->getColor()->getColor());
        }
        $eventEntity->setFgColor('black');
        $eventEntity->setUrl($this->router->generate("event_show", ['id'=>$userEvent->getId()])); // url to send user to when event label is clicked

        $calendarEvent->addEvent($eventEntity);
    }
}
