<?php

namespace App\Service;

use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Chrome\ChromeOptions;

/**
 * Description of WebDriver
 *
 * @author avemar
 */
class WebDriver
{
    public static function getWebDriver()
    {
        $desiredCapabilities = DesiredCapabilities::chrome();
        $options = new ChromeOptions();
        $options->addArguments(['--window-size=1920,1080', '--ignore-certificate-errors']);
        $desiredCapabilities->setCapability(ChromeOptions::CAPABILITY, $options);

        return RemoteWebDriver::create(
            'http://localhost:4444/wd/hub',
            $desiredCapabilities
        );
    }
}
