<?php

trait Links {

    public function testLinks() {
        if ($this->verbose)
            echo "\n" . __TRAIT__ . "\n";
        $this->assertTrue(true);
    }

    public function testLinkEventsAreGenerated() {
        $this->url('html/test_form_events.html');
        $eventLog = $this->elementById('eventlog');
        $eventLog->clear();

        $this->clickOnElement('theLink');
        $alert = $this->getWebDriver()->switchTo()->alert();
        $this->assertEquals('link clicked', $alert->getText());
        $alert->accept();
        $this->assertContains('area', $eventLog->getTagName());
        $this->assertContains('{click(theLink)}', $eventLog->getAttribute('value'));
    }

}
