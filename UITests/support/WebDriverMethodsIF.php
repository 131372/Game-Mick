<?php

/**
 * Description of WebDriverMethodsIF
 *
 * @author jacob
 */
trait WebDriverMethodsIF {

    private function url($url) {
        $this->getWebDriver()->get($this->urlBase . $url);
    }

    private function click($element) {
//print_r($element);
        $this->getWebDriver()->getMouse()->click($element->getCoordinates());
    }

    private function byCssSelector($css) {
        return WebDriverBy::cssSelector($css);
    }

    private function elementByCssSelector($css) {
        return $this->getWebDriver()->findElement(WebDriverBy::cssSelector($css));
    }

    private function elementByCss($css) {
        return $this->elementByCssSelector($css);
    }

    private function byXPath($xp) {
        return $this->getWebDriver()->findElement(WebDriverBy::xpath($xp));
    }

    private function elementByName($xp) {
        return $this->getWebDriver()->findElement(WebDriverBy::name($xp));
    }

    private function byName($xp) {
        return WebDriverBy::name($xp);
    }

    private function byTag($xp) {
        return $this->getWebDriver()->findElement(WebDriverBy::tagName($xp));
    }

    private function byClassName($className) {
        return $this->getWebDriver()->findElement(WebDriverBy::className($className));
    }

    private function elementById($id) {
        return $this->getWebDriver()->findElement(WebDriverBy::id($id));
    }

    private function byId($id) {
        return WebDriverBy::id($id);
    }

    private function elements() {
        return $this->getWebDriver()->findElements();
    }

    private function findElement(WebDriverBy $by) {
        return $this->getWebDriver()->findElement($by);
    }

    private function findElements($str) {
        return $this->getWebDriver()->findElements(WebDriverBy::cssSelector($str));
    }

    private function getCurrentUrl() {
        return $this->getWebDriver()->getCurrentUrl();
    }

    private function title() {
        return $this->getWebDriver()->getTitle();
    }

    private function getTitle() {
        return $this->getWebDriver()->getTitle();
    }

    private function selectById($id) {
        $s = new WebDriverSelect($this->findElement($this->byId($id)));
        return $s;
    }

    private function selectByCss($css) {
        $s = new WebDriverSelect($this->findElement($this->byCssSelector($css)));
        return $s;
    }

    private function selectBy(WebDriverBy $by) {
        $s = new WebDriverSelect($this->findElement($by));
        return $s;
    }

    private function select(WebDriverBy $by) {
        $v = $by->getValue();
        $el = $this->findAnElement($v);        
        $s = new WebDriverSelect($el);
        return $s;
    }

    private function selectedLabels($txt) {
        
    }

    private function selectByVisibleText($txt) {
        return;
    }

    private function using($sel) {
        return $this->getWebDriver()->findElements(WebDriverBy::getMechanism());
    }

    private function value(WebDriverBy $by) {
        return $by->getValue();
    }

    private function execute(Array $cmd) {
        $this->getWebDriver()->execute($cmd['script'], $cmd['args']);
    }

    private function findAnElement($el) {
        $session = $this->getWebdriver();
        $methods = array('linkText', 'id', 'tagName', 'name', 'partialLinkText', 'xpath');
        $methods = array('linkText', 'id', 'tagName', 'name', 'partialLinkText', 'xpath');
        $found = false;
        foreach ($methods as $m) {
            try {
                $by = WebDriverBy::$m($el);
                $element = $session->findElement($by);
                $found = true;
                break;  //leav for each if found)
            } catch (NoSuchElementException $e) {
                ;
            }
        }
        if ($found)
            return $element;
        throw new NoSuchElementException("no element found with '$el'");
    }

    private function clickOnElement($el) {
        $el = $this->findAnElement($el);
        $el->click();
    }

    private function elementNr($nr) {
        try {
            $this->webDriver()->findElement($nr);
        } catch (NoSuchElementException $ex) {
            ;
        }
    }

    private function getFrameElement($x) {
        //print_r($x);
        if ($x === '') {
            
        } else if (is_int($x)) {
            
        } else if (is_array($x)) {
            foreach ($x as $el) {
                
            }
        } else {
            if ($x instanceof RemoteWebElement) {
                try {
                    $id = $x->getAttribute('id');
                    //print_r($id);print "\n";
                    if ($id == '')
                        throw new NoSuchElementException("mo id", 3);
                    $by = WebDriverBy::id($id);
                } catch (NoSuchElementException $ex) {
                    try {
                        $name = $x->getAttribute('name');
                        //print_r($name);print "\n";
                        $by = WebDriverBy::name($name);
                    } catch (NoSuchElementException $ex) {
                        throw new NoSuchElementException();
                    }
                }
                try {
                    $session = $this->getWebDriver();
                    //print_r($by);print "\n";
                    return $session->findElement($by);
                } catch (Exception $e) {
                    ;
                }
            }
        }
    }

    private function takeScreenShot($fname = "img.jpg", $dir = "/tmp") {
        $s = $this->getWebDriver()->takeScreenShot();
        $f = fopen($dir . "/" . $fname, "wb");
        fwrite($f, $s);
        fflush($f);
        fclose($f);
    }

}
