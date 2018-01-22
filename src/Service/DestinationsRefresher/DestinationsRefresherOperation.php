<?php

namespace App\Service\DestinationsRefresher;

use Psr\Log\LoggerInterface;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverWait;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\Exception\WebDriverException;

use App\Service\WebDriver;
use App\Entity\Destination;

/**
 * Description of DestinationsRefresherOperation
 *
 * @author avemar
 */
class DestinationsRefresherOperation
{
    private $airlineId;
    private $webDriver;
    private $webDriverWait;
    private $logger;
    private $destinations = [];

    public function __construct($airlineId, LoggerInterface $logger)
    {
        $this->airlineId = $airlineId;
        $this->webDriver = WebDriver::getWebDriver();
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
                $originField = $this->webDriver->findElement(
                    WebDriverBy::name($formElementFieldName)
                );
                $originField->click();
                
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
                    $originXpath = $domNode->getNodePath();
                    $webDriverDestination = $this->webDriver->findElement(
                        WebDriverBy::xpath($originXpath)
                    );

                    preg_match(
                        '/(?:\b[A-Z]{3,}\b)/',
                        $webDriverDestination->getText(),
                        $iataCode
                    );

                    $this->appendDestination(array_pop($iataCode), $originXpath);
                }

                return $this->destinations;
        }
    }

    public function closeOperation()
    {
        $this->webDriver->quit();
    }

    private function appendDestination($iataCode, $originXpath)
    {
        $this->destinations[] = [
            'iata' => $iataCode,
            'origin_xpath' => $originXpath,
        ];
    }
}
