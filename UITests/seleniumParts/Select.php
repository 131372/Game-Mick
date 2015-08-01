<?php

trait Select {

    public function testSelect() {
        if ($this->verbose)
            echo "\n" . __TRAIT__ . "\n";
        $this->assertTrue(true);
    }

    public function testSelectOptionsInMultiselect() {
        $this->url('html/test_multiselect.html');
//$this->select($this->getById('theSelect'))->selectOptionByValue("option1");
//        $x = $this->findElement($this->getById('theSelect')); // ->selectByValue("option1");
        $select = $this->selectById('theSelect');
        $x = $select->selectByValue("option1");

        $selectedOptionsObjects = $select->getAllSelectedOptions(); // selectedLabels();

        $selectedOptions = array_map(function($obj) {
            return $obj->getText();
        }, $selectedOptionsObjects);
        $this->assertEquals(array('First Option', 'Second Option'), $selectedOptions);
        $select->selectByVisibleText("Fourth Option");
        $selectedOptionsObjects = $select->getAllSelectedOptions(); //selectedLabels();
        $selectedOptions = array_map(function($obj) {
            return $obj->getText();
        }, $selectedOptionsObjects);
        $this->assertEquals(array('First Option', 'Second Option', 'Fourth Option'), $selectedOptions);
    }

    public function testGetSelectedOptionDataInMultiselect() {
        $this->url('html/test_multiselect.html');
        $session = $this->getWebDriver();
        $el = $this->elementById('theSelect');
        $select = $this->selectById('theSelect');
        $option = $select->getFirstSelectedOption();
        $this->assertSame('Second Option', $option->getText());
        $this->assertSame('option2', $option->getAttribute('value'));
        $this->assertSame('o2', $option->getAttribute('id'));

        $select->deSelectAll();
        $this->setExpectedException('NoSuchElementException');
        $option = $select->getFirstSelectedOption();
        $this->assertSame('', $option->getText());
        $this->assertSame('', $option->getAttribute('value'));
        $this->assertSame('', $option->getAttribute('id'));
    }

    public function testClearMultiselectSelectedOptions() {
        $this->url('html/test_multiselect.html');
        $select = $this->selectById('theSelect');
        $selectedOptions = $select->getAllSelectedOptions();
        $selectedOptions = array_map(function($obj) {
            return $obj->getText();
        }, $selectedOptions);
        $this->assertEquals(array('Second Option'), $selectedOptions);
        $select->deSelectAll();
        $selectedOptions = $select->getAllSelectedOptions();
        $selectedOptions = array_map(function($obj) {
            return $obj->getText();
        }, $selectedOptions);

        $this->assertEquals(array(), $selectedOptions);
    }

    public function testASelectObjectCanBeBuildWithASpecificAPI() {
        $this->url('html/test_select.html');
        $el = $this->byCssSelector('select');
        //print_r($el);
        $select = $this->selectBy($el);

        // basic      
        $this->assertEquals('Second Option', $select->getFirstSelectedOption()->getText());

        $this->assertEquals('option2', $select->getFirstSelectedOption()->getAttribute('value'), "1");

        // by text, value attribute or generic criteria
        $select->selectByVisibleText('Fourth Option');
        $this->assertEquals('option4', $select->getFirstSelectedOption()->getAttribute('value'), "2");

        $select->selectByValue('option3');
        $this->assertEquals('Third Option', $select->getFirstSelectedOption()->getText(), "3");

        $selectElement = $this->elementById('o4');      // id are unique
        $this->assertEquals('option4', $selectElement->getAttribute('value'), "4");

        // empty values
        $select->selectByValue('');
        $this->assertEquals('Empty Value Option', $select->getFirstSelectedOption()->getText(), "5");

        $select->selectByVisibleText('');
        $this->assertEquals('option8', $select->getFirstSelectedOption()->getAttribute('value'), "6");
    }

    public function testSelectOptionSelectsDescendantElement() {
        $this->url('html/test_select.html');
        $select = $this->selectBy($this->byCssSelector('#secondSelect'));
        //print_r($select);
        $el = $select->getFirstSelectedOption();
        $this->assertEquals("option2", $el->getAttribute('value'));

        $select->selectByVisibleText("First Option");   // find and select Element
        $el = $select->getFirstSelectedOption();
        $this->assertEquals("option1", $el->getAttribute('value'));

        $select->selectByValue("option2");
        $el = $select->getFirstSelectedOption();
        $this->assertEquals("option2", $el->getAttribute('value'));
    }

    public function testSelectOptgroupDoNotGetInTheWay() {
        $this->url('html/test_select.html');
        $select = $this->selectBy($this->byCssSelector('#selectWithOptgroup'));

        $select->selectByVisibleText("Second");
        $el = $select->getFirstSelectedOption();
        $this->assertEquals("2", $el->getAttribute('value'));

        $select->selectByValue("1");
        $el = $select->getFirstSelectedOption();
        $this->assertEquals("1", $el->getAttribute('value'));
    }

    public function testSelectOptgroupDoNotGetInTheWayII() {
        $this->url('html/test_select.html');
        $select = $this->selectBy($this->byId('selectWithOptgroup'));

        $select->selectByVisibleText("Second");
        $el = $select->getFirstSelectedOption();
        $this->assertEquals("2", $el->getAttribute('value'));

        $select->selectByValue("1");
        $el = $select->getFirstSelectedOption();
        $this->assertEquals("1", $el->getAttribute('value'));
    }

    public function testSelectElements() {
        $this->url('html/test_select.html');
        $session = $this->getWebDriver();
        $option = $session->findElement($this->byId('o2'));

        $this->assertEquals('Second Option', $option->getText());
        $this->assertEquals('option2', $option->getAttribute('value'));
        $this->assertTrue($option->isSelected());

        $option = $session->findElement($this->byId('o3'));
        //->getFirstSelectedOption();
        $this->assertFalse($option->isSelected());
        $option->click();
        $this->assertTrue($option->isSelected());
    }

    public function testSelecThenOptiontElements() {
        $this->url('html/test_select.html');
        $session = $this->getWebDriver();
        $option = $session->findElement(WebDriverBy::cssSelector('select'))
                ->findElement(WebDriverBy::id('o2'));
        $option->click();
        $this->assertEquals('Second Option', $option->getText());
        $this->assertEquals('option2', $option->getAttribute('value'));
        $this->assertTrue($option->isSelected());

        $option = $session->findElement(WebDriverBy::cssSelector('select option[id="o2"]'));
        $option->click();
        $this->assertEquals('Second Option', $option->getText());
        $this->assertEquals('option2', $option->getAttribute('value'));
        $this->assertTrue($option->isSelected());

        $by1 = WebDriverBy::cssSelector('select[name="secondSelect"]');
        $this->selectBy($by1)->deselectByValue($option->getAttribute('value'));
        $this->assertTrue($option->isSelected());

        $element = $session->findElement($by1)
                ->findElement(WebDriverBy::cssSelector('option[value="option3"]'));
        $element->click();

        $this->assertEquals('Second Option', $option->getText());
        $this->assertEquals('option2', $option->getAttribute('value'));
        $this->selectBy($by1)->deselectByValue($option->getAttribute('value'));
        // TODO $this->assertFalse($option->isSelected(), "option 3 is now selected, not option 2");

        $this->assertEquals('Third Option', $element->getText());
        $this->assertEquals('option3', $element->getAttribute('value'));
        $this->assertTrue($element->isSelected());
    }

}
