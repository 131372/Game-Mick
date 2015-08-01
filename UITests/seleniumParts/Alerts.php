<?php

trait Alerts {

    public function testAlerts() {
        if ($this->verbose)
            echo "\n" . __TRAIT__ . "\n";
        $this->assertTrue(true);
    }

    public function testDoubleclickAlert() {
        $this->url('html/test_doubleclick.html');
        $link = $this->byId('link');

        $mouse = $this->getWebDriver()->getMouse();
        $mouse->mouseMove($link->getCoordinates());
        $mouse->doubleClick();

        $alert = $this->getWebDriver()->switchTo()->alert();
        $this->assertEquals('doubleclicked', $alert->getText());
        $alert->accept();
    }

    public function testConfirmationsAreHandledAsAlerts() {
        $this->url('html/test_confirm.html');
        $this->clickOnElement('confirmAndLeave');
        $this->assertEquals('You are about to go to a dummy page.', $this->alertText());
        $this->dismissAlert();
        $this->assertEquals('Test Confirm', $this->title());

        $this->clickOnElement('confirmAndLeave');
        $this->assertEquals('You are about to go to a dummy page.', $this->alertText());
        $this->acceptAlert();
        $this->assertEquals('Dummy Page', $this->title());
    }

    public function testPromptsCanBeAnsweredByTyping() {
        $this->url('html/test_prompt.html');

        $this->clickOnElement('promptAndLeave');
        $this->assertEquals("Type 'yes' and click OK", $this->alertText());
        $this->dismissAlert();
        $this->assertEquals('Test Prompt', $this->title());

        $this->clickOnElement('promptAndLeave');
        $this->alertText('yes');
        $this->acceptAlert();
        $this->assertEquals('Dummy Page', $this->title());
    }

}
