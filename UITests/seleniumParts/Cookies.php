<?php

trait Cookies {

    public function testCookies() {
        if ($this->verbose)
            echo "\n" . __TRAIT__ . "\n";
        $this->assertTrue(true);
    }

    public function testAdvancedParametersOfCookieCanBeSet() {
        $this->url('/');

        $cookies = $this->getWebDriver()->manage();
        $cookies->addCookie(
                array('name' => 'name',
                    'value' => 'x',
                    'path' => '/html',
                    'domain ' => '127.0.0.1',
                    'expiry' => time() + 60 * 60 * 24,
                    'secure' => FALSE)
        );

        $this->assertEmpty($cookies->getCookieNamed('name'));
        $this->url('/html');
        $this->assertEquals('x', $cookies->getCookieNamed('name')['value']);
    }

    public function testCookiesCanBeDeletedOneAtTheTime() {
        $this->url('html/');
        $session = $this->getWebDriver();
        $cookies = $session->manage();

        $v = array('name' => 'c1', 'value' => '1'); // can not be a digit
        $cookies->addCookie($v);
        $cookies->deleteCookieNamed('c1');
        $x = $cookies->getCookieNamed('c1');

        //$this->assertThereIsNoCookieNamed('name');
    }

    public function testCookiesCanBeDeletedAllAtOnce() {
        $this->url('html/');
        $session = $this->getWebDriver();
        $cookies = $session->manage();

        $cookies->addCookie(array('name' => 'id', 'value' => 'id_value'));
        $cookies->addCookie(array('name' => 'name', 'value' => 'name_value'));
        $cookies->deleteAllCookies();
        $this->assertEmpty($cookies->getCookieNamed('id'));
        $this->assertEmpty($cookies->getCookieNamed('name'));
    }

    public function testCookiesCanBeSetAndRead() {
        $this->url('html/');
        $session = $this->getWebDriver();
        $cookies = $session->manage();
        $cookies->addCookie(array('name' => 'nameOfCookieEntry', 'value' => 'valueAUHW'));
        $this->assertEquals('valueAUHW', $cookies->getCookieNamed('nameOfCookieEntry')['value']);
    }

}
