<?php

namespace App\Service\DestinationsSelector;

use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverWait;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Psr\Log\LoggerInterface;
use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Airline;
use App\Entity\Airport;
use App\Entity\Destination;
use App\Service\DestinationsRefresher\DestinationsRefresherOperation;

/**
 * Description of DestinationsSelector
 *
 * @author avemar
 */
class DestinationsSelector
{
    private $logger;    
    private $em;
    private $airlineRepo;
    private $airportRepo;
    private $destinationRepo;
    private $airline;

    public function __construct(
        EntityManagerInterface $em,
        LoggerInterface $logger
    ) {
        $this->em = $em;
        $this->airlineRepo = $em->getRepository(Airline::class);
        $this->airportRepo = $em->getRepository(Airport::class);
        $this->destinationRepo = $em->getRepository(Destination::class);
        $this->logger = $logger;
    }
}
