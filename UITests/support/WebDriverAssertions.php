<?php

trait WebDriverAssertions {

    protected function assertElementNotFound($by) {
        $els = $this->getWebDriver()->findElements($by);
        if (count($els)) {
            //print_r($by);
            $elName = $by->getValue();
            $this->fail("Unexpectedly element '$elName' was found");
        }
        // increment assertion counter
        $this->assertTrue(true);
    }

    protected function assertElementFound($by) {
        $els = $this->getWebDriver()->findElements($by);
        if (count($els) == 0) {
            $elName = $by->getValue();
            $this->fail("Unexpectedly element '$elName' was not found");
        }
        // increment assertion counter
        $this->assertTrue(true);
    }

}
