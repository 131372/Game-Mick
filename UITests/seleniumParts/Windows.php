<?php

trait Windows {

    public function testWindows() {
        if ($this->verbose)
            echo "\n" . __TRAIT__ . "\n";
        $this->assertTrue(true);
    }

    private function window($s) {
        $this->getWebDriver()->get($s);
    }

    public function testACurrentWindowHandleAlwaysExist() {
        $this->url('html/test_open.html');
        $window = $this->getWebDriver()->getWindowHandle();
        $this->assertTrue(is_string($window));
        $this->assertTrue(strlen($window) > 0);
// print_r($window); geeft '{lange hex string}'; wellicht session ID
        $allHandles = $this->getWebDriver()->getWindowHandles();
        $this->assertEquals(array('0' => $window), $allHandles);
    }

    public function testWindowsCanBeManipulatedAsAnObject() {
        $this->url('html/test_select_window.html');
        $session = $this->getWebDriver();
        $session->findElement(WebDriverBy::id('popupPage'))->click();

        $main = $session->switchTo()->defaultContent();
        $this->assertEquals('Select Window Base', $main->getTitle());
        $handle = $session->getWindowHandle();
        $popup = $session->switchTo()->window('myPopupWindow')->manage()->window();

        // print_r($popup->getSize());
        //$popup = $this->currentWindow();
        //$this->assertTrue($popup instanceof PHPUnit_Extensions_Selenium2TestCase_Window);
        $popup->setSize(new WebDriverDimension(150, 200));
        //print_r($popup->getSize());
        $size = $popup->getSize();
        $this->assertEquals(150, $size->getWidth());
        $this->assertEquals(200, $size->getHeight());

        $main = $session->switchTo()->window($handle);
        $this->assertEquals('Select Window Base', $main->getTitle());
    }

    public function testDifferentWindowsCanBeFocusedOnDuringATest() {
        $this->url('html/test_select_window.html');
        $session = $this->getWebDriver();

        $w = $session->getWindowHandle();
        $session->findElement(WebDriverBy::id('popupPage'))->click();

        //$this->window('myPopupWindow');
        $session->switchTo()->window('myPopupWindow');
        $this->assertEquals('Select Window Popup', $this->getTitle(), "myPopupWIndow");
        //$this->takeScreenShot();

        $session->switchTo()->window($w);
        $this->assertEquals('Select Window Base', $this->getTitle(), "back to base I");
        //$this->takeScreenShot('img2');
        $session->switchTo()->window('myPopupWindow');
        $session->findElement(WebDriverBy::id('closePage'))->click();

        $session->switchTo()->window($w);
        $this->assertEquals('Select Window Base', $this->getTitle(), "back to base II");
    }

    public function testWindowsCanBeClosed() {
        $this->url('html/test_select_window.html');
        $session = $this->getWebDriver();
        $w = $session->getWindowHandle();
        $this->elementById('popupPage')->click();

        $pw = $session->switchTo()->window('myPopupWindow');
        $popup = $pw->manage()->window();
        //print_r($popup);
        $pw->close();
        $session->switchTo()->window($w);

        $this->assertEquals('Select Window Base', $this->title());
        $this->assertEquals(1, count($session->getWindowHandles()));
    }

}
