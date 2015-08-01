<?php

trait Click {

    public function testClick() {
        if ($this->verbose)
            echo "\n" . __TRAIT__ . "\n";
        $this->assertTrue(true);
    }

    public function testClickBasic() {
        $this->url('html/test_click_page1.html');
        $link = $this->elementById('link');
        $link->click();
        $this->assertEquals('Click Page Target', $this->getTitle());
        $back = $this->elementById('previousPage');
        $back->click();
        $this->assertEquals('Click Page 1', $this->getTitle());

        $withImage = $this->elementById('linkWithEnclosedImage');
        $withImage->click();
        $this->assertEquals('Click Page Target', $this->getTitle());
        $back = $this->elementById('previousPage');
        $back->click();

        $enclosedImage = $this->elementById('enclosedImage');
        $enclosedImage->click();
        $this->assertEquals('Click Page Target', $this->getTitle());
        $back = $this->elementById('previousPage');
        $back->click();

        $toAnchor = $this->elementById('linkToAnchorOnThisPage');
        $toAnchor->click();
        $this->assertEquals('Click Page 1', $this->getTitle());

        $withOnClick = $this->elementById('linkWithOnclickReturnsFalse');
        $withOnClick->click();
        $this->assertEquals('Click Page 1', $this->getTitle());
    }

    public function testDoubleclick() {
        $this->url('html/test_doubleclick.html');
        $link = $this->elementById('link');

        $mouse = $this->getWebDriver()->getMouse();
        $mouse->mouseMove($link->getCoordinates());
        $mouse->doubleClick();

        $alert = $this->getWebDriver()->switchTo()->alert();
        $this->assertEquals('doubleclicked', $alert->getText());
        $alert->accept();
    }

}
