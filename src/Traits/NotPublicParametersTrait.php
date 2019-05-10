<?php
/**
 * Created by PhpStorm.
 * User: mlocmelis
 * Date: 3/1/19
 * Time: 10:21 AM
 */

namespace Tests\Neznajka\Unit\Traits;


use ReflectionClass;
use Tests\Neznajka\Unit\Traits\CodeceptionClass\UnitTrait;

trait NotPublicParametersTrait
{
    use UnitTrait;

    /**
     * @param object $object
     * @param string $property
     * @return mixed
     * @throws \InvalidArgumentException
     * @throws \ReflectionException
     */
    protected function getNotPublicValue($object, string $property)
    {
        $reflection = new ReflectionClass($object);

        if ($reflection->hasProperty($property)) {
            $reflectionProperty = $reflection->getProperty($property);
            $reflectionProperty->setAccessible(true);

            return $reflectionProperty->getValue($object);
        }

        $staticProperties = $reflection->getStaticProperties();
        if (array_key_exists($property, $staticProperties)) {
            return $staticProperties[$property];
        }

        throw new \InvalidArgumentException("property with name {$property} does not exists in class {$reflection->getName()}");
    }

    /**
     * @param object $object
     * @param string $property
     * @param mixed $value
     * @throws \InvalidArgumentException
     * @throws \ReflectionException
     */
    protected function setNotPublicValue($object, string $property, $value)
    {
        $reflection = new ReflectionClass($object);

        if ($reflection->hasProperty($property)) {
            $propertyReflection = $reflection->getProperty($property);
            $propertyReflection->setAccessible(true);
            $propertyReflection->setValue($object, $value);

            return ;
        }

        $message = "property with name {$property} does not exists in class {$reflection->getName()}, static unsupported";
        throw new \InvalidArgumentException($message);
    }
}
