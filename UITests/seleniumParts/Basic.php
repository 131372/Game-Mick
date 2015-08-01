<?php

trait Basic {

    public function testBasic() {
        if ($this->verbose)
            echo "\n" . __TRAIT__ . "\n";
        $this->assertTrue(true);
    }

    /**
     * open two urls. the latter should be active.
     * This is a test for the webdriver.
     */
    public function testMultipleUrlsCanBeLoadedInATest() {
        $session = $this->getWebDriver();
        $this->url('html/test_click_page1.html');
        $this->url('html/test_open.html');
        $this->assertEquals('Test open', $this->title());
        $this->assertStringEndsWith('html/test_open.html', strstr($session->getCurrentUrl(), "two's URLs should take the latter"));
    }

    public function testNonexistentElement() {

        $this->url('html/test_open.html');
        $this->setExpectedException('NoSuchElementException');
        $el = $this->elementById("nonexistent");
        // exception should be raised. No code executed (so no fail)
        $this->fail('The element shouldn\'t exist.');
    }

    public function testOpen() {
        $this->url('html/test_open.html');
        $theUrl = $this->getCurrentURL();
        $this->assertStringEndsWith('html/test_open.html', $theUrl, "got $theUrl");
    }

    public function testVersionCanBeReadFromTheTestCaseClass() {
        $this->markTestIncomplete(
                "have to figure out how to get the version of this webdriver"
        );
        // may be something in 
        // https://github.com/facebook/php-webdriver/tree/master/lib 
//$this->assertEquals(1, version_compare(PHPUnit_Extensions_Selenium2TestCase::VERSION, "1.2.0"));
    }

    public function testCamelCaseUrlsAreSupported() {
        $this->url('html/CamelCasePage.html');
        $this->assertStringEndsWith('html/CamelCasePage.html', $this->getCurrentURL(), "camelCase.html ending");
        $this->assertEquals('CamelCase page', $this->title(), "check on CamelCase page Title");
    }

    public function testAbsoluteUrlsAreSupported() {
        $this->markTestIncomplete(
                "how to code that absolute URLS are checked?"
        );
        return;
        $this->url(__DIR__ . '/html/test_open.html');
        $this->assertEquals('Test open', $this->getWebDriver()->getTitle());
    }

    public function testElementsKnowTheirSize() {
        $this->url('html/test_geometry.html');
        $element = $this->elementById('rectangle');
        $size = $element->getSize();
        $this->assertEquals(200, $size->getWidth());
        $this->assertEquals(100, $size->getHeight());
    }

    public function testElementsKnowTheirCssPropertiesValues() {
        $this->url('html/test_geometry.html');
        $element = $this->elementById('colored');
        $this->assertRegExp('/rgba\(0,\s*0,\s*255,\s*1\)/', $element->getCssValue('background-color'));
    }

    public function testElementSelectionByCssSelector() {
        $this->url('html/test_open.html');
        $element = $this->elementByCssSelector('body');
        $this->assertEquals('This is a test of the open command.', $element->getText());
    }

    public function testElementSelectionByID() {
        $this->url('html/test_click_page1.html');
        $link = $this->elementById('link');
        $this->assertEquals('Click here for next page', $link->getText());
    }

    public function testMultipleElementsSelection() {
        $this->url('html/test_element_selection.html');
//$elements = $this->elements($this->using('css selector')->getValue('div'));
        $elements = $this->findElements('div');
//print_r($elements);
        $this->assertEquals(4, count($elements));
        $this->assertEquals('Other div', $elements[0]->getText());
    }

    public function testByLinkText() {
        $this->url('html/test_click_page1.html');
        $link = $this->findElement(WebDriverBy::linkText('Click here for next page'));
        $link->click();
        $this->assertEquals('Click Page Target', $this->getTitle());
    }

    public function testByPartialLinkText() {
        $this->url('html/test_click_page1.html');
        $link = $this->findElement(WebDriverby::partialLinkText('next page'));
        $link->click();
        $this->assertEquals('Click Page Target', $this->getTitle());
    }

    public function testTheElementWithFocusCanBeInspected() {
        $this->url('html/test_select.html');

        // Select input and check if active
        $theInput = $this->elementByCssSelector('input[name="theInput"]');
        $theInput->click();
        
        $s = $this->getWebDriver()->getActiveElement();
        $this->assertEquals($theInput->getText(), $s->getText(), 'Input not recognized as active.');
        $this->assertTrue($s->equals($theInput), 'Input not recognized as active 2.');
        $this->assertTrue($theInput->equals($s), 'Input not recognized as active 3.');

        // Select select-group and check if active
        $selectGroup = $this->elementByCssSelector('#selectWithOptgroup');
        $selectGroup->click();

        $s = $this->getWebDriver()->getActiveElement();
        $this->assertEquals($s->getText(), $selectGroup->getText(), 'Select-group not recognized as active.');

        // Make sure that input is not recognized as selected
        //$s = $this->getWebDriver()->getActiveElement(); // ->equals($theInput);
        $this->assertNotEquals($s->getText(), $theInput->getText(), 'Input falsely recognized as active.');
    }

    public function testElementsCanBeSelectedAsChildrenOfAlreadyFoundElements() {
        $this->url('html/test_element_selection.html');
        $parent = $this->elementByCssSelector('div#parentElement');

        $child = $parent->findElement(WebDriverBy::tagName('span'));
//$child = $parent->element($this->using('css selector')->value('span'));
        $this->assertEquals('Child span', $child->getText());

        $rows = $this->elementByCssSelector('table')->findElements(WebDriverBy::tagName('tr'));
        $this->assertEquals(2, count($rows));
    }

    public function testTheBackAndForwardButtonCanBeUsedToNavigate() {
        $this->url('html/test_click_page1.html');
        $this->assertEquals('Click Page 1', $this->title());

        $this->clickOnElement('link');
        $this->assertEquals('Click Page Target', $this->title());

        $this->getWebDriver()->navigate()->back();
        $this->assertEquals('Click Page 1', $this->title());

        $this->getWebDriver()->navigate()->forward();
        $this->assertEquals('Click Page Target', $this->title());
    }

    public function testThePageCanBeRefreshed() {
        $this->url('html/test_page.slow.html');
        $this->assertStringEndsWith('html/test_page.slow.html', $this->getWebDriver()->getCurrentUrl());
        $this->assertEquals('Slow Loading Page', $this->title());

        $this->clickOnElement('changeSpan');
        $this->assertEquals('Changed the text', $this->elementById('theSpan')->getText());
        $this->getWebDriver()->navigate()->refresh();
        $this->assertEquals('This is a slow-loading page.', $this->elementById('theSpan')->getText());

        $this->clickOnElement('changeSpan');
        $this->assertEquals('Changed the text', $this->elementById('theSpan')->getText());
    }

    public function test404PagesCanBeLoaded() {
        $this->url('inexistent.html');
    }

    public function testScreenshotsCanBeTakenAtAnyMoment() {
        $this->url('html/test_open.html');
        $screenshot = $this->getWebDriver()->takeScreenshot();
        $this->assertTrue(is_string($screenshot));
        $this->assertTrue(strlen($screenshot) > 0);
        //$this->markTestIncomplete('By guaranteeing the size of the window, we could add a deterministic assertion for the image.');
    }

    public function testThePageSourceCanBeRead() {
        $this->url('html/test_open.html');
        $source = $this->getWebDriver()->getPageSource();
        $this->assertStringStartsWith('<!--', $source);
        $this->assertContains('<body>', $source);
        $this->assertStringEndsWith('</html>', $source);
    }

    /**
     * Test on Session and Element
     *
     * @dataProvider getObjectsWithAccessToElement
     */
    public function testShortenedApiForSelectionOfElement($factory) {
        $this->url('html/test_element_selection.html');
        $parent = $factory($this);

        $element = $parent->findElement(WebDriverBy::className('theDivClass'));
        $this->assertEquals('The right div', $element->getText());

        $element = $parent->findElement(WebDriverBy::cssSelector('div.theDivClass'));
        $this->assertEquals('The right div', $element->getText());

        $element = $parent->findElement(WebDriverBy::id('theDivId'));
        $this->assertEquals('The right div', $element->getText());

        $element = $parent->findElement(WebDriverBy::name('theDivName'));
        $this->assertEquals('The right div', $element->getText());

        $element = $parent->findElement(WebDriverBy::tagName('div'));
        $this->assertEquals('Other div', $element->getText());

        $element = $parent->findElement(WebDriverBy::xPath('//div[@id]'));
        $this->assertEquals('The right div', $element->getText());
        $element = $parent->findElement(WebDriverBy::linkText('a href link entry'));
        $this->assertEquals('a href link entry', $element->getText());
        $element = $parent->findElement(WebDriverBy::partialLinkText('a href link entry'));
        $this->assertEquals('a href link entry', $element->getText());
        $element = $parent->findElement(WebDriverBy::partialLinkText('href link entry'));
        $this->assertEquals('a href link entry', $element->getText());
        $element = $parent->findElement(WebDriverBy::partialLinkText('a href link '));
        $this->assertEquals('a href link entry', $element->getText());
    }

    public function getObjectsWithAccessToElement() {
        return array(
            array(function($s) {
                    return $s;
                }),
            array(function($s) {
                    return $s->byXPath('//body');
                })
        );
    }

    public function testElementsKnowTheirTagName() {
        $this->url('html/test_element_selection.html');
        $session = $this->getWebDriver();
        $element = $session->findElement(WebDriverBy::className('theDivClass'));
        $this->assertEquals('div', $element->getTagName());
    }

    public function testFormElementsKnowIfTheyAreEnabled() {
        $this->url('html/test_form_elements.html');
        $this->assertTrue($this->elementById('enabledInput')->isEnabled());
        $this->assertFalse($this->elementById('disabledInput')->isEnabled());
    }

    public function testElementsKnowTheirAttributes() {
        $this->url('html/test_element_selection.html');
        $element = $this->elementById('theDivId');
        $this->assertEquals('theDivClass', $element->getAttribute('class'));
    }

    public function testElementsDiscoverTheirEqualityWithOtherElements() {
        $this->url('html/test_element_selection.html');
        $element = $this->elementById('theDivId');
        $differentElement = $this->elementById('parentElement');
        $equalElement = $this->elementById('theDivId');
        $this->assertTrue($element->equals($equalElement));
        $this->assertFalse($element->equals($differentElement));
    }

    public function testElementsKnowWhereTheyAreInThePage() {
        $this->url('html/test_element_selection.html');
        $element = $this->elementByCssSelector('body');
        $location = $element->getLocation();
        $this->assertEquals(0, $location->getX());
        $this->assertEquals(0, $location->getY());
    }

}
