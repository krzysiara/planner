<?php
/**
 *
 * ICal Service
 *
 */

namespace AppBundle\Service;

use AppBundle\Entity\Event;
use AppBundle\Entity\Profile;
use AppBundle\Repository\EventRepository;
use Welp\IcalBundle\Component\Calendar;
use Welp\IcalBundle\Factory\Factory;

/**
 * iCalService
 */
class ICalService
{

    /**
     * Event repository
     * @var EventRepository
     */
    private $eventRepository;

    /**
     * ical Factory
     * @var Factory $icalFactory
     * */
    private $icalFactory;

    /**
     * iCalService constructor.
     * @param EventRepository $eventRepository
     * @param Factory         $icalFactory
     */
    public function __construct(EventRepository $eventRepository, Factory $icalFactory)
    {

        $this->eventRepository = $eventRepository;
        $this->icalFactory = $icalFactory;
    }

    /**
     * create Calendar
     *
     * @param Profile $profile
     * @return Calendar
     */
    public function createICal($profile)
    {
        //Create a calendar
        $calendar = $this->icalFactory->createCalendar();
        $calendar = $this->addEvents($calendar, $profile);

        return $calendar;
    }

    /**
     * Add Events
     *
     * @param $calendar Calendar
     * @param $profile Profile
     * @return Calendar
     */
    private function addEvents($calendar, $profile)
    {
        $events = $this->eventRepository->findByProfile($profile);
        foreach ($events as $event) {
            $calendarEvent = $this->createEvent($event);
            $calendar->addEvent($calendarEvent);
        }

        return $calendar;
    }

    /**
     * Create Event
     *
     * @param $event Event
     * @return \Jsvrcek\ICS\Model\CalendarEvent
     */
    private function createEvent($event)
    {
        $calendarEvent = $this->icalFactory->createCalendarEvent();
        $calendarEvent->setStart($event->getStartDate())
            ->setSummary($event->getTitle())
            ->setUid('event-uid'.$event->getId());
        if ($event->getEndDate()) {
            $calendarEvent ->setEnd($event->getEndDate());
        }

        return $calendarEvent;
    }
}
