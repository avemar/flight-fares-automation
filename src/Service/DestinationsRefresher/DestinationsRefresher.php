<?php

namespace App\Service\DestinationsRefresher;

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
 * Description of RefreshDestinations
 *
 * @author avemar
 */
class DestinationsRefresher {
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

    public function refresh($airlineId)
    {
        $this->fetchAirline($airlineId);

        $operation = new DestinationsRefresherOperation(
            $airlineId,
            $this->logger
        );

        $operation->goToUrl($this->getAirlineUrl());
        $operation->goToDestinationsUrl();
        $this->saveFromDestinations($operation->getFromDestinations());
        $operation->closeOperation();
        return true;
    }

    private function getAirlineUrl()
    {
        return $this->airline->getUrl();
    }

    private function fetchAirline($airlineId)
    {
        $this->airline = $this->airlineRepo->find($airlineId);
    }
    
    private function saveFromDestinations(array $fromDestinations)
    {
        if (empty($fromDestinations)) {
            return;
        }

        foreach ($fromDestinations as $fromDestination) {
            $destinationAirport = $this->airportRepo->findOneByIata(
                $fromDestination['iata']
            );

            if (is_null($destinationAirport)) {
                // log airport not found
                continue;
            }

            $destination = $this->destinationRepo->findOneBy([
                'airport' => $destinationAirport,
                'airline' => $this->airline,
            ]);
            
            if (is_null($destination)) {
                $destination = new Destination();

                $destination
                    ->setAirline($this->airline)
                    ->setAirport($destinationAirport)
                    ->setOriginXpath($fromDestination['origin_xpath']);
             
                $this->em->persist($destination);
                continue;
            }

            $destination->setOriginXpath($fromDestination['origin_xpath']);
        }

        $this->em->flush();
    }
}
