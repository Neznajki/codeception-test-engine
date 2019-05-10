<?php
/**
 * Created by PhpStorm.
 * User: mlocmelis
 * Date: 3/18/19
 * Time: 10:47 AM
 */

namespace Tests\Neznajka\Unit\ValueObject;

use ReflectionClass;

/**
 * Class TestCaseFunctionData
 * @package Tests\Neznajka\Unit\ValueObject
 */
class TestCaseClassData
{
    /** @var ReflectionClass */
    private $classReflection;

    /**
     * TestCaseClassData constructor.
     * @param string $className
     * @throws \ReflectionException
     */
    public function __construct(string $className)
    {
        $this->classReflection = new ReflectionClass($className);
    }

    /**
     * @return ReflectionClass
     */
    public function getClassReflection(): ReflectionClass
    {
        return $this->classReflection;
    }

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return $this->classReflection->getName();
    }

    /**
     * @return string
     */
    public function getNameSpace(): string
    {
        return $this->getClassReflection()->getNamespaceName();
    }
}
