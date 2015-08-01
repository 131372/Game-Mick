<?php

trait Session {

    public function testSession() {
        if ($this->verbose)
            echo "\n" . __TRAIT__ . "\n";
        $this->assertTrue(true);
    }

}
