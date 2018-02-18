<?php

namespace App\Service\DestinationsRefresher;

use Psr\Log\LoggerInterface;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverWait;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\Exception\WebDriverException;

use App\Service\WebDriver;
use App\Service\DestinationsSelector\DestinationsSelectorOperation;

/**
 * Description of DestinationsRefresherOperation
 *
 * @author avemar
 */
class DestinationsRefresherOperation
{
    private $airlineId;
    private $webDriver;
    private $webDriverSession;
    private $webDriverWait;
    private $logger;
    private $fromDestinations = [];
    private $routes = [];

    public function __construct($airlineId, LoggerInterface $logger)
    {
        $this->airlineId = $airlineId;
        $this->webDriver = WebDriver::getWebDriver();
        $this->webDriverSession = $this->webDriver->getSessionID();
        $this->webDriverWait = new WebDriverWait($this->webDriver, 5);
        $this->logger = $logger;
        libxml_use_internal_errors(true);
    }

    public function goToUrl($url)
    {
        $this->webDriver->get($url);
    }

    public function goToDestinationsUrl()
    {
        try {
            switch ($this->airlineId) {
                // Sri Lankan Airlines
                case 4349:
                    $globalSiteFlagXpath = "//*[@id=\"ip_capture_country_list\"]/div[27]/a";

                    $this->webDriverWait->until(
                        WebDriverExpectedCondition::visibilityOfElementLocated(
                            WebDriverBy::xpath($globalSiteFlagXpath)
                        )
                    );
                    $flag = $this->webDriver->findElement(
                        WebDriverBy::xpath($globalSiteFlagXpath)
                    );
                    $flag->click();
                    break;
            }
        } catch (WebDriverException $e) {            
            $this->logger->error($e);
        }
    }

    public function getFromDestinations()
    {
        switch ($this->airlineId) {
            // Sri Lankan Airlines
            case 4349:
                $formElementFieldName = 'suggest1';
                
                $this->webDriverWait->until(
                    WebDriverExpectedCondition::visibilityOfElementLocated(
                        WebDriverBy::name($formElementFieldName)
                    )
                );
                $fromField = $this->webDriver->findElement(
                    WebDriverBy::name($formElementFieldName)
                );
                $fromField->click();
                
                $destinationsCollectionXpath = "/html/body/div[5]/ul";                
                $this->webDriverWait->until(
                    WebDriverExpectedCondition::presenceOfElementLocated(
                        WebDriverBy::xpath($destinationsCollectionXpath)
                    )
                );

                $dom = new \DOMDocument;
                $dom->loadHTML($this->webDriver->getPageSource());
                $xpath = new \DOMXPath($dom);

                $destinationXpath = $destinationsCollectionXpath . "/li";
                $domListDestinations = $xpath->query($destinationXpath);

                foreach ($domListDestinations as $domNode) {
                    $xpath = $domNode->getNodePath();
                    $webDriverDestination = $this->webDriver->findElement(
                        WebDriverBy::xpath($xpath)
                    );

                    preg_match(
                        '/(?:\b[A-Z]{3,}\b)/',
                        $webDriverDestination->getText(),
                        $iataCode
                    );

                    $this->appendFromDestination(array_pop($iataCode));
                }

                return $this->fromDestinations;
        }
    }

    public function getRoutes()
    {
        $destSelectorOp = new DestinationsSelectorOperation(
            $this->airlineId,
            $this->logger,
            $this->webDriver
        );

        switch ($this->airlineId) {
            // Sri Lankan Airlines
            case 4349:
                $formElementFieldName = 'suggest2';
                $toField = $this->webDriver->findElement(
                    WebDriverBy::name($formElementFieldName)
                );
                $destinationsCollectionXpath = "/html/body/div[6]/ul";

                foreach ($this->fromDestinations as $fromDestination) {
                    if (!$destSelectorOp->selectFromDestination($fromDestination['iata'])) {
                        // From destination not found. Consider logging
                        continue;
                    }

                    $toField->click();

                    $this->webDriverWait->until(
                        WebDriverExpectedCondition::presenceOfElementLocated(
                            WebDriverBy::xpath($destinationsCollectionXpath)
                        )
                    );

                    $dom = new \DOMDocument;
                    $dom->loadHTML($this->webDriver->getPageSource());
                    $xpath = new \DOMXPath($dom);

                    $destinationXpath = $destinationsCollectionXpath . "/li";
                    $domListDestinations = $xpath->query($destinationXpath);

                    foreach ($domListDestinations as $domNode) {
                        $xpath = $domNode->getNodePath();
                        $webDriverDestination = $this->webDriver->findElement(
                            WebDriverBy::xpath($xpath)
                        );

                        preg_match(
                            '/(?:\b[A-Z]{3,}\b)/',
                            $webDriverDestination->getText(),
                            $iataCode
                        );

                        $this->appendRoute(
                            $fromDestination['iata'],
                            array_pop($iataCode)
                        );
                    }
                }

                return $this->routes;
        }
    }

    public function closeOperation()
    {
        $this->webDriver->quit();
    }

    private function appendFromDestination($iataCode)
    {
        $this->fromDestinations[] = [
            'iata' => $iataCode,
        ];
    }

    private function appendRoute($fromIataCode, $toIataCode)
    {
        $this->routes[] = [
            'from_iata' => $fromIataCode,
            'to_iata' => $toIataCode,
        ];
    }
}
