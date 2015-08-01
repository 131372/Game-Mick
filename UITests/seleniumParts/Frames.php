<?php

trait Frames {

    public function testFrames() {
        if ($this->verbose)
            echo "\n" . __TRAIT__ . "\n";
        $this->assertTrue(true);
    }

    public function testDifferentFramesFromTheMainOneCanGetFocusById() {
        $this->url('html/test_frames.html');
        $session = $this->getWebDriver();
        $frame = $session->switchTo()->frame('my_iframe_name');
        $this->assertEquals('This is a test of the open command.', $frame->findElement(WebDriverBy::cssSelector('body'))->getText()
                , "switched to 'my_iframe_id");

        /** TODO: check NULL FRAME */
        $r = $session->switchTo()->defaultContent();    // frame(NULL);
        $this->assertContains('This page contains frames.', $r->findElement(WebDriverBy::cssSelector('body'))->getText()
                , "switched to ''");
        $this->assertContains('This page contains frames.', $session->findElement(WebDriverBy::cssSelector('body'))->getText()
                , "switched to ''.2");
    }

    public function testDifferentFramesFromTheMainOneCanGetFocusByFrameCount() {
        $this->url('html/test_frames.html');
        $session = $this->getWebDriver();
        $frames = $session->findElements(WebDriverBy::cssSelector('iframe'));
        $frame0 = $this->getFrameElement($frames[0]);

        $r = $session->switchTo()->frame($frame0);
        $t = $r->findElement(WebDriverBy::cssSelector('body'))->getText();
        $this->assertEquals('This is a test of the open command.', $t, "switched TO (0)");

        $r = $session->switchTo()->defaultContent();
        $this->assertContains('This page contains frames.', $this->elementByCssSelector('body')->getText());
    }

    public function testDifferentFramesFromTheMainOneCanGetFocusByFrameCounting() {
        $checks = array(
            'This is a test of the open command.'
            , 'This is a dummy page.'
            , 'Click here for next page via absolute link'
        );
        $this->url('html/test_frames.html');
        $session = $this->getWebDriver();
        $frames = $session->findElements(WebDriverBy::cssSelector('iframe'));
        foreach ($frames as $k => $f) {
            $frame = $this->getFrameElement($f);

            $r = $session->switchTo()->frame($frame);
            $t = $r->findElement(WebDriverBy::cssSelector('body'))->getText();
            $this->assertContains($checks[$k], $t, "switched To $k");

            $r = $session->switchTo()->defaultContent();
            $t = $this->elementByCssSelector('body')->getText();
            $this->assertContains('This page contains frames.', $t);
        }
    }

    public function testDifferentFramesFromTheMainOneCanGetFocusByName() {
        $this->url('html/test_frames.html');
        $session = $this->getWebDriver();
        $frame = $session->switchTo('my_iframe_name')->frame('my_iframe_name');
        $this->assertEquals('This is a test of the open command.', $frame->findElement(WebDriverBy::cssSelector('body'))->getText()
                , "switched To 'my_iframe_name'");

        $session->switchTo()->defaultContent();
        $this->assertContains('This page contains frames.', $this->elementByCssSelector('body')->getText());
    }

    public function testDifferentFramesFromTheMainOneCanGetFocusByElement() {
        $this->url('html/test_frames.html');
        $session = $this->getWebDriver();
        $frame = $session->switchTo('my_iframe_id')->frame('my_iframe_name');

        $this->assertEquals('This is a test of the open command.', $frame->findElement(WebDriverBy::cssSelector('body'))->getText());

        $session->switchTo()->defaultContent();
        $this->assertContains('This page contains frames.', $this->elementByCssSelector('body')->getText());
    }

}
