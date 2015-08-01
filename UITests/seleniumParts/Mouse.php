<?php

trait Mouse {

    public function testMouse() {
        if ($this->verbose)
            echo "\n" . __TRAIT__ . "\n";
        $this->assertTrue(true);
    }

    public function testMouseButtonsCanBeClickedMultipleTimes() {
        $this->url('html/test_doubleclick.html');
        //$this->markTestIncomplete();
        $session = $this->getWebDriver();
        $mouse = $session->getMouse();
        $homeCoordinates = $this->elementByCss('body')->getCoordinates();
        $linkCoordinates = $this->elementById('link')->getCoordinates();
        $aHrefCoordinates = $this->elementById('ahref')->getCoordinates();

        $mouse->mouseMove($linkCoordinates);
        $mouse->doubleClick();
        $alert = $session->switchTo()->alert(); // must be OK
        $this->assertEquals('doubleclicked', $alert->getText());
        $alert->accept();

//print __METHOD__ . $linkCoordinates->inViewPort()->getX();
        $mouse->mouseMove($aHrefCoordinates);
        $mouse->doubleClick();

        $alert = $session->switchTo()->alert(); // must be OK
        $this->assertContains('doubleclicked', $alert->getText());
        $alert->accept();


        $mouse->mouseMove($homeCoordinates);
        /*
          var_dump($homeCoordinates);
          $mouse->doubleClick();
          $alert = $session->switchTo()->alert(); // must be OK
          $this->assertEquals('doubleclicked', $alert->getText());
          $alert->accept();
         */


        $mouse->mouseMove($linkCoordinates, 600, 600);
        $this->setExpectedException('NoAlertOpenException');
        $mouse->doubleClick();
        $alert = $session->switchTo()->alert(); // must fail
        $alert->accept();   // if it fails, the box is closed
    }

    public function testSessionClick() {
        $this->url('html/test_mouse_buttons.html');
        $session = $this->getWebDriver();
        $mouse = $session->getMouse();
        $input = $this->elementById('input');
        $check = $this->elementById('check');

        //$this->moveto($input);

        $input->click();    // left click
        $this->assertEquals('0', $check->getText());

        //$input->click(WebDriver::LEFT);
        //$this->assertEquals('0', $check->getText());
        // I couldn't get it worked in selenium webdriver 2.28: even though the client (phpunit-selenium) sends
        // the button: 1 in the request (checked with wireshark) - it still uses left mouse button (0)
        /*
          $this->click(PHPUnit_Extensions_Selenium2TestCase_SessionCommand_Click::MIDDLE);
          $this->assertEquals('1', $this->byId('check')->text());
         */

        $mouse->contextClick();     //WebDriver::RIGHT);
        $this->assertEquals('2', $check->getText());
    }

    public function _testMoveToRequiresElementParamToBeValidElement() {
        $this->url('html/test_moveto.html');
        $session = $this->getWebDriver();
        $mouse = $session->getMouse();
        $this->setExpectedException('NoSuchElementException');
        $coordinates = $this->elementById('nonExistingId')->getCoordinates();
        $mouse->mouseMove($coordinates);
        $this->fail('A single non-element parameter should cause an exception');
    }

    public function testTheMouseCanBeMovedToAKnownPosition() {
        // @TODO: remove markTestIncomplete() when the following bugs are fixed
        // @see https://code.google.com/p/selenium/issues/detail?id=5939
        // @see https://code.google.com/p/selenium/issues/detail?id=3578
        $this->markTestIncomplete(__METHOD__ . 'This is broken in a firefox driver yet');

        $this->url('html/test_moveto.html');
        $session = $this->getWebDriver();
        $mouse = $session->getMouse();
        //$c = new WebDriverCoordinates
        $coord = $this->elementById('moveto')->getCoordinates();
        $mouse->mouseMove($coord, 10, 10);
        $mouse->mouseDown();

        $deltaX = 42;
        $deltaY = 11;
        $mouse->mouseMove(null, $deltaX, $deltaY);
        $mosue->mouseUp();

        $down = explode(',', $this->elementById('down')->getText());
        $up = explode(',', $this->elementById('up')->getText());

        $this->assertCount(2, $down);
        $this->assertCount(2, $up);
        $this->assertEquals($deltaX, $up[0] - $down[0]);
        $this->assertEquals($deltaY, $up[1] - $down[1]);
    }

}
