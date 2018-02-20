<?php

namespace App\Service\Tester;

use Psr\Log\LoggerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Facebook\WebDriver\WebDriver;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverWait;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\Interactions\WebDriverActions;

use App\Service\WebDriver as AppWebDriver;
use App\Entity\Airline;
use App\Entity\Airport;
use App\Entity\Destination;
use App\Entity\Route;
use App\Service\DestinationsRefresher\DestinationsRefresherOperation;
use App\Service\DestinationsSelector\DestinationsSelectorOperation;

/**
 * Description of Tester
 *
 * @author avemar
 */
class Tester
{
    private $logger;    
    private $em;
    private $airlineRepo;
    private $airportRepo;
    private $destinationRepo;
    private $routeRepo;
    private $webDriver;
    private $webDriverWait;

    public function __construct(
        EntityManagerInterface $em,
        LoggerInterface $logger,
        WebDriver $webDriver = null
    ) {
        $this->em = $em;
        $this->airlineRepo = $em->getRepository(Airline::class);
        $this->airportRepo = $em->getRepository(Airport::class);
        $this->destinationRepo = $em->getRepository(Destination::class);
        $this->routeRepo = $em->getRepository(Route::class);
        $this->logger = $logger;        
    }

    public function execute($testId)
    {
        $this->webDriver = AppWebDriver::getWebDriver();
        $this->webDriverWait = new WebDriverWait($this->webDriver, 5);
        
        switch ($testId) {
            case 1:
                $airlineId = 4349;
                
                $this->airline = $this->airlineRepo->find($airlineId);
                $operation = new DestinationsRefresherOperation(
                    $airlineId,
                    $this->logger,
                    $this->webDriver
                );

                $operation->goToUrl($this->airline->getUrl());
                $operation->goToDestinationsUrl();

                $destSelectorOp = new DestinationsSelectorOperation(
                    $airlineId,
                    $this->logger,
                    $this->webDriver
                );
                
                $destSelectorOp->selectFromDestination('SHJ');

                $operation->closeOperation();
                
                return true;
        }

        return false;
    }
}
