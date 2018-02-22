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
use App\Entity\Route;
use App\Service\DestinationsRefresher\DestinationsRefresherOperation;

/**
 * Description of RefreshDestinations
 *
 * @author avemar
 */
class DestinationsRefresher
{
    private $logger;    
    private $em;
    private $airlineRepo;
    private $airportRepo;
    private $destinationRepo;
    private $routeRepo;
    private $airline;

    public function __construct(
        EntityManagerInterface $em,
        LoggerInterface $logger
    ) {
        $this->em = $em;
        $this->airlineRepo = $em->getRepository(Airline::class);
        $this->airportRepo = $em->getRepository(Airport::class);
        $this->destinationRepo = $em->getRepository(Destination::class);
        $this->routeRepo = $em->getRepository(Route::class);
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
        $this->saveRoutes($operation->getRoutes());
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
                // TODO log airport not found
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
                    ->setAirport($destinationAirport);
                    //->setOriginXpath($fromDestination['xpath']);

                $this->em->persist($destination);
                continue;
            }

            // Not used at the moment
            // $destination->setOriginXpath($fromDestination['xpath']);
        }

        $this->em->flush();
    }

    private function saveRoutes(array $routes)
    {
        if (empty($routes)) {
            return;
        }

        $i = 0;

        foreach ($routes as $route) {
            $i++;            
            $fromAirport = $this->airportRepo->findOneByIata(
                $route['from_iata']
            );
            $toAirport = $this->airportRepo->findOneByIata(
                $route['to_iata']
            );

            if (is_null($fromAirport) || is_null($toAirport)) {
                // TODO log airport not found
                continue;
            }

            $route = $this->routeRepo->findOneBy([
                'airline' => $this->airline,
                'fromAirport' => $fromAirport,
                'toAirport' => $toAirport,
            ]);

            if (is_null($route)) {
                $route = new Route();

                $route
                    ->setAirline($this->airline)
                    ->setFromAirport($fromAirport)
                    ->setToAirport($toAirport);

                $this->em->persist($route);
                continue;
            }

            if ($i % 50 === 0) {
                $this->em->flush();
            }
            // TODO implement update with active / inactive flag
        }

        $this->em->flush();
    }
}
