<?php

trait webDriverIF {

    private static $mSECONDS = 50; // in msec
    protected static $driver;

    protected static function setWebDriver($d) {
        self::$driver = $d;
    }

    protected static function getWebDriver() {
        return self::$driver;
    }

    protected function doSetUp() {
        $capabilities = array(
            \WebDriverCapabilityType::BROWSER_NAME => 'firefox' // self::$browser
        );
        self::setWebDriver(
                RemoteWebDriver::create('http://localhost:4444/wd/hub', $capabilities, self::$mSECONDS)
        );
    }

    protected function doTearDown() {
        $wd = self::getWebDriver();
        $wd->close();
        $wd->quit();
    }

    public static function setUpBeforeClass() {
        self::doSetUp();
    }

    public static function tearDownAfterClass() {
        self::doTearDown();
    }

}
