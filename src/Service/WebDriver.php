<?php

namespace App\Service;

use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;

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

        return RemoteWebDriver::create(
            'http://localhost:4444/wd/hub',
            $desiredCapabilities
        );
    }
}
