<?php

namespace App\EventListener;

use App\Event\AddPersonneEvent;
use Psr\Log\LoggerInterface;

class PersonneListener
{
    public function __construct(private LoggerInterface $logger){}

    public function onPersonneAdd(AddPersonneEvent $event ){
        $this->logger->debug(" Cc je suis entrain d'ecouter l'evenement personne.add et une  personne vient d'etre ajoutée est". $event->getPersonne()->getName());
    }
}