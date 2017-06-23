<?php
/**
 * Event Notification Service
 */

namespace AppBundle\Service;

use AppBundle\Entity\User;
use AppBundle\Repository\EventRepository;
use AppBundle\Repository\UserRepository;

/**
 * Event Notification Service class
 */
class EventNotification
{
    /** usr Repository
     * @var UserRepository
     */
    private $userRepository;

    /**
     * eventRepository
     * @var EventRepository
     */
    private $eventsRepository;

    /**
     * Mailer
     * @var Mailer
     */
    private $mailer;

    /**
     * Twig
     * @var Twig
     */
    private $twig;


    /**
     * EventNotification constructor.
     * @param Twig            $twig
     * @param Mailer          $mailer
     * @param UserRepository  $userRepository
     * @param EventRepository $eventRepository
     */
    public function __construct($twig, $mailer, UserRepository $userRepository, EventRepository $eventRepository)
    {
        $this->userRepository = $userRepository;
        $this->eventsRepository = $eventRepository;
        $this->twig = $twig;
        $this->mailer = $mailer;
    }

    /**
     * Send Reminders
     */
    public function sendNotifications()
    {
        $users = $this->getUsers();
        foreach ($users as $user) {
            $this->sendUserNotification($user);
        }
    }

    /**
     * sendUserNotification
     * @param $user User
     */
    private function sendUserNotification($user)
    {
        $events = $this->getTodayEvents($user);

        if ($events) {
            $message = $this->createNotificationMessage($events, $user);
            $this->mailer->send($message);
        }
    }

    /**
     * getTodayEvents
     * @param User $user User
     * @return array
     */
    private function getTodayEvents($user)
    {
        return $this->eventsRepository->findTodayEvents($user->getProfile(), new \DateTime());
    }

    /**
     * getUsers
     * @return array
     */
    private function getUsers()
    {
        return $this->userRepository->findWithActiveReminderSettings();
    }

    /**
     * createNotificationMessage
     * @param $events
     * @param $user User
     * @return \Swift_Message
     */
    private function createNotificationMessage($events, $user)
    {
        $message = new \Swift_Message('Events notification');
        $message->setFrom('planner@mail.com')
            ->setTo($user->getEmail())
            ->setBody(
                $this->twig->render(
                    // app/Resources/views/Emails/reminder.html.twig
                    'Emails/reminder.html.twig',
                    array('events' => $events)
                ),
                'text/html'
            );

        return $message;
    }
}
