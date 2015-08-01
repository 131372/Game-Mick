<?php

trait getPrivateProperty {

    public function getPrivateProperty($class, $propertyName) {
        if (is_object($class)) {
            $className = get_class($class);
        } else {
            $className = $class;
        }
        $reflector = new ReflectionClass($className);

        $property = $reflector->getProperty($propertyName);
        $property->setAccessible(true);
        return $property;
    }

    public function getPrivatePropertyValue($class, $propertyName) {
        $property = $this->getPrivateProperty($class, $propertyName);

        if ($property->isStatic()) {
            return $property->getValue();
        } else {
            if (is_object($class)) {
                return $property->getValue($class);
            } else {
                throw new Exception("can not get property of $class->$propertyName");
            }
        }
    }

}
