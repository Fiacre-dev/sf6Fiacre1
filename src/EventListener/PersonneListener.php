<?php

namespace App\EventListener;

use App\Event\AddPersonneEvent;
use App\Event\ListAllPersonnesEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Event\KernelEvent;

class PersonneListener
{
    public function __construct(private LoggerInterface $logger){}

    public function onPersonneAdd(AddPersonneEvent $event ){
        $this->logger->debug(" Cc je suis entrain d'ecouter l'evenement personne.add et une  personne vient d'etre ajoutée est". $event->getPersonne()->getName());
    }

    public function onListAllPersonnes(ListAllPersonnesEvent $event ){
        $this->logger->debug(" Le nombre de personnes dans la base est". $event->getNbPersonne());
    }

    public function onListAllPersonnes2(ListAllPersonnesEvent $event ){
        $this->logger->debug("Le second Listener avec le nombre :".$event->getNbPersonne());
    }

    public function logKernelRequest(KernelEvent $event ){
        dd($event->getRequest());
    }


}