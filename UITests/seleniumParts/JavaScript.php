<?php

trait JavaScript {

    public function testJavaScript() {
        if ($this->verbose)
            echo "\n" . __TRAIT__ . "\n";
        $this->assertTrue(true);
    }

    public function testFromTutorial() {
        // https://github.com/facebook/php-webdriver/wiki/Example-command-reference
        $this->url('html/test_open.html');
        $script = 'arguments[arguments.length-1]("done")';
        $sResult = $this->getWebDriver()->executeAsyncScript($script, array());
        $this->assertEquals("done", $sResult, "facebook example");
    }

    public function testFromTutorialII() {
        //  https://github.com/facebook/php-webdriver/wiki/Example-command-reference
// define the javascript code to execute. This just checks at a periodic
// interval to see if your page created the window.MY_STUFF_DONE variable
        $sJavascript = <<<END_JAVASCRIPT
    // webdriver async script callback
    var callback = arguments[arguments.length-1];
    var nIntervalId; // setInterval id to stop polling

    function checkDone() {
        if( window.MY_STUFF_DONE ) {
            window.clearInterval(nIntervalId); // stop polling
            callback("done"); // return "done" to PHP code
        }            
        window.MY_STUFF_DONE = 1;            
    }

    nIntervalId = window.setInterval( checkDone, 50 );   // start polling
                
END_JAVASCRIPT;

        $session = $this->getWebDriver();
        // wait at most 5 seconds before giving up with a timeout exception
        $session->manage()->timeouts()->setScriptTimeout(5);
        $sResult = $session->executeAsyncScript($sJavascript, array());
        //$session->executeScript("window.MY_STUFF_DONE = 1;", array());
        $this->assertEquals("done", $sResult);
    }

    public function testClicksOnJavaScriptHref() {
        $this->url('html/test_click_javascript_page.html');
        $this->clickOnElement('link');
        $this->assertEquals('link clicked', $this->elementById('result')->getText());
    }

    public function testJavaScriptCanBeEmbeddedForExecution() {
        $this->url('html/test_open.html');
        $script = 'return document.title;';
        $result = $this->getWebDriver()->executeScript($script, array());
        $this->assertEquals("Test open", $result);
    }

    public function testAsynchronousJavaScriptCanBeEmbeddedForExecution() {
        $this->url('html/test_open.html');
        $script = 'var callback = arguments[0]; callback(document.title);';
        $result = $this->getWebDriver()->executeAsyncScript($script, array());
        $this->assertEquals("Test open", $result);
    }

}
