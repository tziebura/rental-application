<?php

namespace App\Tests;

use ReflectionProperty;

trait PrivatePropertyManipulator
{
    public function setByReflection($object, string $property, $value): void
    {
        $reflectionProperty = $this->getAccessibleReflectionProperty($object, $property);

        $reflectionProperty->setValue($object, $value);
    }

    public function getByReflection($object, string $property)
    {
        $reflectionProperty = $this->getAccessibleReflectionProperty($object, $property);

        return $reflectionProperty->getValue($object);
    }

    private function getAccessibleReflectionProperty($object, string $property): ReflectionProperty
    {
        $reflectionProperty = new ReflectionProperty($object, $property);
        $reflectionProperty->setAccessible(true);

        return $reflectionProperty;
    }
}