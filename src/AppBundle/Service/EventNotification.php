<?php
/**
 * Created by PhpStorm.
 * Event Notification Service
 */

namespace AppBundle\Service;

use AppBundle\Entity\User;
use AppBundle\Repository\EventRepository;
use AppBundle\Repository\UserRepository;

class EventNotification
{
    private $userRepository;
    private $eventsRepository;
    private $mailer;
    private $twig;


    /**
     * EventNotification constructor.
     * @param $twig
     * @param $mailer
     * @param UserRepository  $userRepository
     * @param EventRepository $eventRepository
     */
    public function __construct($twig, $mailer, UserRepository $userRepository, EventRepository $eventRepository)
    {
        $this->userRepository=$userRepository;
        $this->eventsRepository=$eventRepository;
        $this->twig=$twig;
        $this->mailer=$mailer;
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
     * @param $user User
     * @return array
     */
    private function getTodayEvents($user)
    {
        return $this->eventsRepository->findTodayEvents($user->getProfile(), new \DateTime());
    }

    /**
     * @return array
     */
    private function getUsers()
    {
        return $this->userRepository->findWithActiveReminderSettings();
    }

    /**
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
            )
        ;
        return $message;
    }
}
