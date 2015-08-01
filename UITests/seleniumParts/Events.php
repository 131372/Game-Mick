<?php

trait Events {

    public function testEvents() {
        if ($this->verbose)
            echo "\n" . __TRAIT__ . "\n";
        $this->assertTrue(true);
    }

    public function testButtonEventsAreGenerated() {
        $this->url('html/test_form_events.html');
        $eventLog = $this->elementById('eventlog');
        $eventLog->clear();

        $this->clickOnElement('theButton');
        $this->assertContains('{focus(theButton)}', $eventLog->getAttribute('value'));
        $this->assertContains('{click(theButton)}', $eventLog->getAttribute('value'));
        $eventLog->clear();

        $this->clickOnElement('theSubmit');
        $this->assertContains('{focus(theSubmit)} {click(theSubmit)} {submit}', $eventLog->getAttribute('value'));
    }

    public function testCheckboxEventsAreGenerated() {
        $this->markTestIncomplete("Flaky: fails on focus in some browsers.");
        $this->url('html/test_form_events.html');
        $checkbox = $this->byId('theCheckbox');
        $eventLog = $this->byId('eventlog');
        $this->assertFalse($checkbox->isSelected());
        $this->assertEquals('', $eventLog->value());

        $checkbox->click();
        $this->assertContains('{focus(theCheckbox)}', $eventLog->value());
        $this->assertContains('{click(theCheckbox)}', $eventLog->value());
        $this->assertContains('{change(theCheckbox)}', $eventLog->value());

        $eventLog->clear();
        $checkbox->click();
        $this->assertContains('{focus(theCheckbox)}', $eventLog->value());
        $this->assertContains('{click(theCheckbox)}', $eventLog->value());
        $this->assertContains('{change(theCheckbox)}', $eventLog->value());
    }

    public function testSelectEventsAreGeneratedbutOnlyIfANewSelectionIsMade() {
        $this->url('html/test_form_events.html');
        $session = $this->getWebDriver();
        $select = $this->select($this->byId('theSelect'));
        $eventLog = $this->elementById('eventlog');
        $eventLog->clear();

        $select->selectByVisibleText('First Option');
        $option = $select->getFirstSelectedOption();
        $this->assertEquals('option1', $option->getAttribute('value'));
        $this->assertContains('{focus(theSelect)}', $eventLog->getAttribute('value'));
        $this->assertContains('{change(theSelect)}', $eventLog->getAttribute('value'));

        $eventLog->clear();
        $select->selectByVisibleText('First Option');
        $option = $select->getFirstSelectedOption();
        $this->assertEquals('option1', $option->getAttribute('value'));
        $this->assertEquals('', $eventLog->getAttribute('value'));
    }

    public function testRadioEventsAreGenerated() {
        //$this->markTestIncomplete("Flaky: fails on focus in some browsers.");
        $this->url('html/test_form_events.html');
        $first = $this->elementById('theRadio1');
        $second = $this->elementById('theRadio2');
        $eventLog = $this->elementById('eventlog');

        $this->assertFalse($first->isSelected());
        $this->assertFalse($second->isSelected());
        $this->assertEquals('', $eventLog->getAttribute('value'));

        $first->click();
        $this->assertContains('{focus(theRadio1)}', $eventLog->getAttribute('value'));
        $this->assertContains('{click(theRadio1)}', $eventLog->getAttribute('value'));
        $this->assertContains('{change(theRadio1)}', $eventLog->getAttribute('value'));
//        $this->assertNotContains('theRadio2', $eventLog->getAttribute('value'));

        $eventLog->clear();
        $first->click();
        $this->assertContains('{focus(theRadio1)}', $eventLog->getAttribute('value'));
        $this->assertContains('{click(theRadio1)}', $eventLog->getAttribute('value'));
    }

    public function testTextEventsAreGenerated() {
        $this->url('html/test_form_events.html');
        $textBox = $this->elementById('theTextbox');
        $eventLog = $this->elementById('eventlog');
        $eventLog->clear();
        $this->assertEquals('', $textBox->getAttribute('value'));
        $this->assertEquals('', $eventLog->getAttribute('value'));

        $textBox->sendKeys('first value');
        $this->assertContains('{focus(theTextbox)}', $eventLog->getAttribute('value'));
    }

    public function testWaitPeriodsAreImplicitInSelection() {
        $this->url('html/test_delayed_element.html');

        $session = $this->getWebDriver();
        $session->manage()->timeouts()->implicitlyWait(10000);
        $element = $this->elementById('createElementButton');
        $element->click();
        $div = $session->findElement(WebDriverBy::xPath("//div[@id='delayedDiv']"));
        $this->assertEquals('Delayed div.', $div->getText());
    }

    public function _testMouseEventsAreGenerated() {
        $this->url('html/test_form_events.html');

        $this->clickOnElement('theTextbox');
        $this->clickOnElement('theButton');
        $eventLog = $this->elementById('eventlog');

        $this->assertContains('{mouseover(theTextbox)}', $eventLog->getAttribute('value'));
        $this->assertContains('{mousedown(theButton)}', $eventLog->getAttribute('value'));
        $this->assertContains('{mouseover(theTextbox)}', $eventLog->getAttribute('value'));
        $this->assertContains('{mousedown(theButton)}', $eventLog->getAttribute('value'));
    }

}
