<?php

trait InputTyping {

    public function testInputTyping() {
        if ($this->verbose)
            echo "\n" . __TRAIT__ . "\n";
        $this->assertTrue(true);
    }

    public function testSpecialKeys() {
        $session = $this->getWebDriver();
        $ac = $session->action();
        $this->url('html/test_special_keys.html');
        $el = $this->elementById('input');
        $el->click();
        $chk = $this->elementById('check');

        $el->sendKeys(WebDriverKeys::F2);
        $this->assertEquals('113', $chk->getText());


        $el->sendKeys(WebDriverKeys::ALT . WebDriverKeys::ENTER);
        $this->assertEquals('13,alt', $chk->getText());

        // note that modifier keys (alt, control, shift) are sticky
        // so they are enabled until you explicitly disable it by another call
        // The above is valid for at least Chrome and Firefox, in IE they are
        // sticky only within a single keys() method call
        $k = implode(' ', array(WebDriverKeys::ALT, WebDriverKeys::CONTROL,
            WebDriverKeys::SHIFT, WebDriverKeys::HOME));
        $el->sendKeys($k);
        $this->assertEquals('36,alt,control,shift', $chk->getText());

        //$session->action()->keys(Keys::ALT . Keys::SHIFT . Keys::NUMPAD7);
        $k = implode(' ', array(WebDriverKeys::ALT, WebDriverKeys::SHIFT, WebDriverKeys::CONTROL,
            WebDriverKeys::NUMPAD7));
        $el->sendKeys($k);
        //$this->assertEquals('103,control', $chk->getText());
        $this->assertEquals('103,alt,control,shift', $chk->getText());
    }

    public function testTypingNonLatinText() {
        $this->url('html/test_type_page1.html');
        $usernameInput = $this->elementByName('username');
        $usernameInput->sendKeys('テストユーザ');
        $this->assertEquals('テストユーザ', $usernameInput->getAttribute('value'));
    }

    /**
     * @d ep en ds testTypingViaTheKeyboard
     */
    public function testTextTypedInAreasCanBeCleared() {
        $this->url('html/test_type_page1.html');
        $usernameInput = $this->elementByName('username');
        $usernameInput->sendKeys('TestUser');
        $usernameInput->clear();
        $this->assertEquals('', $usernameInput->getText());
    }

    public function testActivePageElementReceivesTheKeyStrokes() {
//$this->timeouts()->implicitWait(10000);

        $this->url('html/test_send_keys.html');
        $this->elementById('q')->click();
        $this->getWebDriver()->getKeyboard()->sendKeys('phpunit ');
        $this->assertEquals('phpunit', $this->elementById('result')->getText());
    }

    public function testKeyEventsAreGenerated() {
        $this->url('html/test_form_events.html');
        $session = $this->getWebDriver();
        $theEventLog = $this->elementById('eventlog');
        $theBox = $this->elementById('theTextbox');
        //$select = $this->selectById('theTextbox');
        //$session->getKeyBoard()->sendKeys('t');
        $theBox->sendKeys('t');
        //       $this->takeScreenShot();
        $txt = $theEventLog->getAttribute('value');
        $exps = array('{focus(theTextbox)}'
            , ' {keydown(theTextbox - 84)}'
            , ' {keypress(theTextbox)}'
            , ' {keyup(theTextbox - 84)}');
        foreach ($exps as $exp) {
            $this->assertContains($exp, $txt);
        }
    }

}
