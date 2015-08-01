<?php

trait Develop {

    private $windowHandle;

    public function testDevelop() {
        $this->setWindowHandle();
        if ($this->verbose)
            echo "\n" . __TRAIT__ . "\n";
        $this->assertTrue(true);
    }

    private function newWindow() {
        $this->setWindowHandle();
        $driver = $this->getWebDriver();
        $oldWindowHandle = $driver->getWindowHandle();

        // open new window
        $this->tabHelper(array(WebDriverKeys::CONTROL, 'n'));
        $newWindowHandle = $driver->getWindowHandle();

        $driver->switchTo()->window($oldWindowHandle);
        $this->closeWindow();


        $driver->switchTo()->window(end($driver->getWindowHandles()));
        //$driver->switchTo()->window($newWindowHandle);
    }

    private function setWindowHandle() {
        $session = $this->getWebDriver();
        $windowHandle = $session->getWindowHandle();
    }

    private function closeWindow() {
        if (isset($this->windowHandle)) {
            $this->getWebDriver()->close();
        }
    }

    private function tabHelper(Array $k, $cnt = 1) {
        $k = implode(' ', $k);
        for ($i = 0; $i < $cnt; $i++) {
            $body = $this->elementByCssSelector('body');
            $body->sendKeys($k);
            $this->refreshTab();
        }
    }

    private function nextTab($nr = 1) {
        $this->tabHelper(array(WebDriverKeys::CONTROL, WebDriverKeys::TAB), $nr);
    }

    private function closeTab() {
        $this->tabHelper(array(WebDriverKeys::CONTROL, 'w'));
    }

    private function prevTab($cnt = 1) {
        $this->tabHelper(array(WebDriverKeys::CONTROL, WebDriverKeys::PAGE_UP), $cnt);
    }

    private function refreshTab() {
        $this->getWebDriver()->navigate()->refresh();
    }

    private function newTab() {
        $this->tabHelper(array(WebDriverKeys::CONTROL, 't'));
    }

    private function playInvoer($naam) {
        $this->url('../index.php');
        $session = $this->getWebDriver();
        $this->clickOnElement('naam');
        $usernameInput = $this->elementByName('naam');
        $usernameInput->sendKeys($naam);
        $this->assertEquals('De titel', $this->title());
        $usernameInput->sendKeys(WebDriverKeys::ENTER);
        $this->assertEquals($naam, $this->title());
    }

    public function testInvoerSpeler() {

        $this->playInvoer('speler1');
        $this->newTab();
        $this->playInvoer('speler2');
        $this->newTab();
        $this->playInvoer('speler3');

//$this->waitForUserInput();
        $this->nextTab();   // back on first
//
// stage is set, now check
//$this->waitForUserInput();
        $this->assertEquals('speler1', $this->elementById('me')->getText(), "screen1; me(speler1)");
        $this->assertEquals('speler2', $this->elementById('joiner1')->getText(), "screen1;speler2");
        $this->assertEquals('speler3', $this->elementById('joiner2')->getText(), "screen2;speler3");

        $this->nextTab();
        $this->assertEquals('speler2', $this->elementById('me')->getText());
        $this->assertEquals('speler1', $this->elementById('joiner1')->getText(), 'screen2;joiner1');
        $this->assertEquals('speler3', $this->elementById('joiner2')->getText(), 'screen2;joiner2');

        $this->nextTab();
        $this->assertEquals('speler3', $this->elementById('me')->getText());
        $this->assertEquals('speler1', $this->elementById('joiner1')->getText(), 'screen3;joiner1');
        $this->assertEquals('speler2', $this->elementById('joiner2')->getText(), 'screen3;joiner2');

        $this->nextTab();
        $this->assertEquals('speler1', $this->elementById('me')->getText(), 'back first screen');

        $this->closeTab();
        $this->closeTab();
        $this->newTab();
        $this->prevTab();
        $this->closeTab();
    }

    public function testJoiningA2B() {
        $this->newWindow();
        $this->playInvoer('spelerA');
        $this->newTab();
        $this->playInvoer('spelerB');
        $this->prevTab();   // back on SpelerA
//$this->waitForUserInput();
        $this->assertEquals('spelerA', $this->elementById('me')->getText());    // back first screen
//$session->wait(20, 1000)->until(WebDriverExpectedCondition::titleIs('WebDriver Page'));

        $this->clickOnElement('join1');
        $this->assertEquals('The Game', $this->title());
        //$this->assertEquals('spelerA', $this->elementById('speler')->getText(), 'PlayerA after JOIN');

        $this->nextTab();
// player must have been joined
        $this->assertEquals('The Game', $this->title());
        //$this->assertEquals('spelerB', $this->elementById('speler')->getText());

        $this->prevTab();
        $this->closeTab();
        $this->newTab();
        $this->prevTab();
        $this->closeTab();
    }

    public function testJoiningB2A() {
        $this->newWindow();
        $this->playInvoer('spelerA');
        $this->newTab();
        $this->playInvoer('spelerB');

        $this->assertEquals('spelerB', $this->elementById('me')->getText());    // back first screen

        $this->clickOnElement('join1');
        $this->assertEquals('The Game', $this->title());
        //$this->assertEquals('spelerB', $this->elementById('speler')->getText());

        $this->prevTab();
        $this->assertEquals('The Game', $this->title());
        //$this->assertEquals('spelerA', $this->elementById('speler')->getText());
    }

    public function testAfterJoining() {
        $this->newWindow();
        $this->playInvoer('spelerA');
        $this->newTab();
        $this->playInvoer('spelerB');
        $this->newTab();
        $this->playInvoer('spelerC');
        $this->newTab();
        $this->playInvoer('spelerD');

        $this->prevTab(3);
        /*
         * nog niet, naam uit database
         */
        //$this->assertEquals('spelerA', $this->elementById('me')->getText());

        $this->clickOnElement('join1');
        $this->assertEquals('The Game', $this->title());
        /*
         * not yet, naam moet uit database komen.
         * */
        //$this->assertEquals('spelerA', $this->elementById('speler')->getText());

        $this->nextTab(2);
        //$this->waitForUserInput();
        $this->assertEquals('spelerC', $this->title());
        $this->assertEquals('spelerD', $this->elementById('joiner1')->getText(), "screen1;spelerD");
// no more choices to 
        $this->setExpectedException('Exception', 'Unable to locate element: {"method":"id","selector":"joiner2"}');
        $el = $this->elementById('joiner2');
    }

}
