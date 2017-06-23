<?php
/**
 * Send Notifications Command
 */

namespace AppBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class SendNotificationsCommand extends ContainerAwareCommand
{

    /**
     * Configure command
     */
    protected function configure()
    {
        $this
            ->setName('app:event:send-notifiacations')
            ->setDescription('Send notifications about upcoming events');
    }


    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $eventNotificationsService = $this->getContainer()->get('app.service.event_notification');
            $eventNotificationsService->sendNotifications();
        } catch (\Exception $e) {
            $output->write("Error ".$e->getCode().": ".$e->getMessage());
        }
        $output->write("Notifications sent \n");
    }
}
