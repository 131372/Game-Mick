<?php

trait Form {

    public function testForm() {
        if ($this->verbose)
            echo "\n" . __TRAIT__ . "\n";
        $this->assertTrue(true);
    }

    public function testTypingViaTheKeyboard() {
        $this->url('html/test_type_page1.html');
        $session = $this->getWebDriver();
        $inputBy = $this->byName('username');
        $usernameInput = $this->findElement($inputBy);
        $usernameInput->click();
        $usernameInput->sendKeys('TestUser');
        //$usernameInput->setValue('TestUser');
        $this->assertEquals('TestUser', $usernameInput->getAttribute('value'));

        $passwordInput = $this->elementByName('password');
        $passwordInput->click();
        $passwordInput->sendKeys('testUserPassword');
        $this->assertEquals('testUserPassword', $passwordInput->getAttribute('value'));

        $this->clickOnElement('submitButton');
        $h2 = $this->elementByCssSelector('h2');
        $this->assertRegExp('/Welcome, TestUser!/', $h2->getText());
    }

    public function testTypingAddsCharactersToTheCurrentValueOfAnElement() {
        $this->url('html/test_type_page1.html');
        $usernameInput = $this->elementByName('username');
        $usernameInput->click();
        $usernameInput->sendKeys('first');
        $usernameInput->sendKeys('Second');
        $this->assertEquals('firstSecond', $usernameInput->getAttribute('value'));
    }

    public function testNumericValuesCanBeTyped() {
        $this->url('html/test_type_page1.html');
        $usernameInput = $this->elementByName('username');
        $usernameInput->sendKeys(1.13);
        $this->assertEquals(1.13, $usernameInput->getAttribute('value'));
    }

    public function testFormsCanBeSubmitted() {
        $this->url('html/test_type_page1.html');
        $usernameInput = $this->elementByName('username');
        $usernameInput->sendKeys('TestUser');

        $this->elementByCssSelector('form')->submit();
        $h2 = $this->elementByCssSelector('h2');
        $this->assertRegExp('/Welcome, TestUser!/', $h2->getText());
    }

    public function testCheckboxesCanBeSelectedAndDeselected() {
        $this->markTestIncomplete("Flaky: fails on clicking in some browsers.");
        $this->url('html/test_check_uncheck.html');
        $beans = $this->byId('option-beans');
        $butter = $this->byId('option-butter');

        $this->assertTrue($beans->isSelected());
        $this->assertFalse($butter->isSelected());

        $butter->click();
        $this->assertTrue($butter->isSelected());
        $butter->click();
        $this->assertFalse($butter->isSelected());
    }

    public function testRadioBoxesCanBeSelected() {
        $this->url('html/test_check_uncheck.html');
        $spud = $this->elementById('base-spud');
        $rice = $this->elementById('base-rice');

        $this->assertTrue($spud->isSelected());
        $this->assertFalse($rice->isSelected());

        $rice->click();
        $this->assertFalse($spud->isSelected());
        $this->assertTrue($rice->isSelected());

        $spud->click();
        $this->assertTrue($spud->isSelected());
        $this->assertFalse($rice->isSelected());
    }

}
