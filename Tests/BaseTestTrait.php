<?php
namespace RS\DiExtraBundle\Tests;

use PHPUnit\Framework\Exception;

trait BaseTestTrait
{
    protected function callObjectMethod($object, $methodName)
    {
        $args = func_get_args();
        array_shift($args); //$object
        array_shift($args); //$methodName
        $reflect = new \ReflectionClass($object);
        $method = $reflect->getMethod($methodName);
        $method->setAccessible(true);
        return $method->invokeArgs($object, $args);
    }

    protected function setObjectAttribute($object, $attributeName, $value, $class = null)
    {
        $reflect = new \ReflectionClass($class===null?$object:$class);
        $property = $reflect->getProperty($attributeName);
        $property->setAccessible(true);
        $property->setValue($object, $value);
    }

    /**
     * Returns the value of an object's attribute.
     * This also works for attributes that are declared protected or private.
     *
     * @param object $object
     * @param string $attributeName
     *
     * @return mixed
     *
     * @throws Exception
     */
    public static function getObjectAttribute($object, $attributeName)
    {
        try {
            $attribute = new \ReflectionProperty($object, $attributeName);
        } catch (\ReflectionException $e) {
            $reflector = new \ReflectionObject($object);

            while ($reflector = $reflector->getParentClass()) {
                try {
                    $attribute = $reflector->getProperty($attributeName);

                    break;
                } catch (\ReflectionException $e) {
                }
            }
        }

        if (isset($attribute)) {
            if (!$attribute || $attribute->isPublic()) {
                return $object->$attributeName;
            }

            $attribute->setAccessible(true);
            $value = $attribute->getValue($object);
            $attribute->setAccessible(false);

            return $value;
        }

        throw new Exception(
            \sprintf(
                'Attribute "%s" not found in object.',
                $attributeName
            )
        );
    }
}