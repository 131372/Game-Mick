<?php

trait CSS {

    public function testCSS() {
        if ($this->verbose)
            echo "\n" . __TRAIT__ . "\n";
        $this->assertTrue(true);
    }

    public function testInvisibleElementsDoNotHaveADisplayedText() {
        $this->url('html/test_visibility.html');
        $this->assertEquals('A visible paragraph', $this->elementById('visibleParagraph')->getText());
        $this->assertTrue($this->elementById('visibleParagraph')->isDisplayed());

        $this->assertEquals('', $this->elementById('hiddenParagraph')->getText());
        $this->assertFalse($this->elementById('hiddenParagraph')->isDisplayed());

        $this->assertEquals('', $this->elementById('suppressedParagraph')->getText());
        $this->assertEquals('', $this->elementById('classSuppressedParagraph')->getText());
        $this->assertEquals('', $this->elementById('jsClassSuppressedParagraph')->getText());
        $this->assertEquals('', $this->elementById('hiddenSubElement')->getText());
        $this->assertEquals('sub-element that is explicitly visible', $this->elementById('visibleSubElement')->getText());
        $this->assertEquals('', $this->elementById('suppressedSubElement')->getText());
        $this->assertEquals('', $this->elementById('jsHiddenParagraph')->getText());
    }

}
