<?php

namespace App\EventSubscriber;

use App\Event\AddPersonneEvent;
use App\service\MailerService;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PersonneEventSubscriber implements EventSubscriberInterface
{
    private MailerService $mailer;

    public function __construct(MailerService $mailer , private LoggerInterface $logger)
    {
        $this->mailer = $mailer;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            AddPersonneEvent::ADD_PERSONNE_EVENT => ['onAddPersonneEvent', 3000]
        ];
    }

    public function onAddPersonneEvent(AddPersonneEvent $event)
    {
        $personne = $event->getPersonne();
        $mailMessage = $personne->getFirstname() . ' ' . $personne->getName() . " a été ajouté avec succes";
        $this->logger->info("Envoie d'amail pour".$personne->getFirstname() . ' ' . $personne->getName());
        $this->mailer->sendEmail(content: $mailMessage, subject: 'Mail sent from EventSubscriber');
    }
}
