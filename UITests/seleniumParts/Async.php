<?php

trait Async {

    public function testAsync() {
        if ($this->verbose)
            echo '\n' . __TRAIT__ . "\n";
        $this->assertTrue(true);
    }

    public function testTimeoutsCanBeDefinedForAsynchronousExecutionOfJavaScript() {
        $this->url('html/test_open.html');
        $this->timeouts()->asyncScript(10000);
        $script = 'var callback = arguments[0];
                   window.setTimeout(function() {
                       callback(document.title);
                   }, 1000);
        ';
        $result = $this->executeAsync(array(
            'script' => $script,
            'args' => array()
        ));
        $this->assertEquals("Test open", $result);
    }

}
