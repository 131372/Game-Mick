<?php

// from http://www.webtipblog.com/unit-testing-private-methods-and-properties-with-phpunit/
// see also http://stackoverflow.com/questions/249664/best-practices-to-test-protected-methods-with-phpunit 
trait getPrivateMethod {

    private function getPrivateMethod($className, $methodName) {
        $reflector = new ReflectionClass($className);
        $method = $reflector->getMethod($methodName);
        $method->setAccessible(true);

        return $method;
    }

}
