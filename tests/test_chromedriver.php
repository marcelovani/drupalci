<?php

require 'vendor/autoload.php'; // Autoload the necessary classes via Composer

use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Chrome\ChromeOptions;

try {
    // Define the URL of the Selenium server that Chromedriver is running on
    $serverUrl = 'http://localhost:9515';

    // Set Chrome options
    $options = new ChromeOptions();
    $options->addArguments([
        '--headless',           // Run Chrome in headless mode
        '--disable-gpu',        // Disable GPU hardware acceleration
        '--window-size=1920x1080', // Optional: Set a window size in headless
        '--no-sandbox',         // Bypass OS security model, useful for CI
        '--disable-dev-shm-usage' // Overcome limited resource problems
    ]);

    // Set desired capabilities with Chrome options
    $capabilities = DesiredCapabilities::chrome();
    $capabilities->setCapability(ChromeOptions::CAPABILITY, $options);

    // Create a new instance of RemoteWebDriver with the Chromedriver server URL and desired capabilities
    $driver = RemoteWebDriver::create($serverUrl, $capabilities);

    // Navigate to a webpage
    $driver->get('https://www.bing.com');

    // Retrieve and print the title of the page
    echo "The title is " . $driver->getTitle() . "\n";

    // Properly close the browser session
    $driver->quit();

} catch (\Exception $e) {
    echo "An error occurred during testing: " . $e->getMessage() . "\n";
}
