<?php

namespace App\Service\DestinationsSelector;

use Psr\Log\LoggerInterface;
use Facebook\WebDriver\WebDriver;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverWait;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\Interactions\WebDriverActions;

use App\Service\WebDriver as AppWebDriver;

/**
 * Description of DestinationsSelectorOperation
 *
 * @author avemar
 */
class DestinationsSelectorOperation
{
    private $airlineId;
    private $webDriver;
    private $webDriverWait;
    private $logger;

    public function __construct(
        $airlineId,
        LoggerInterface $logger,
        WebDriver $webDriver = null
    ) {
        $this->airlineId = $airlineId;
        $this->webDriver = !is_null($webDriver) ? $webDriver : AppWebDriver::getWebDriver();
        $this->webDriverWait = new WebDriverWait($this->webDriver, 5);
        $this->logger = $logger;
        libxml_use_internal_errors(true);
    }

    public function selectFromDestination($iataCode)
    {
        switch ($this->airlineId) {
            // Sri Lankan Airlines
            case 4349:
                $fromField = $this->webDriver->findElement(
                    WebDriverBy::name('suggest1')
                );

                $fromField->clear();
                $fromField->sendKeys($iataCode);

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
                $action = new WebDriverActions($this->webDriver);

                foreach ($domListDestinations as $domNode) {
                    $xpath = $domNode->getNodePath();
                    $webDriverDestination = $this->webDriver->findElement(
                        WebDriverBy::xpath($xpath)
                    );

                    if (strpos(
                        $webDriverDestination->getText(),
                        '(' . $iataCode . ')'
                    ) !== false) {                        
                        $action->moveToElement($webDriverDestination)->perform();
                        $this->webDriverWait->until(
                            WebDriverExpectedCondition::elementToBeClickable(
                                WebDriverBy::xpath($xpath)
                            )
                        );
                        $webDriverDestination->click();
                        return true;
                    }
                }

                return false;
        }
    }
}
