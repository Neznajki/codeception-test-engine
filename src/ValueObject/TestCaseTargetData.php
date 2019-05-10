<?php
/**
 * Created by PhpStorm.
 * User: mlocmelis
 * Date: 3/18/19
 * Time: 10:34 AM
 */

namespace Tests\Neznajka\Unit\ValueObject;

use LogicException;
use ReflectionMethod;

/**
 * Class TestCaseData
 * @package Tests\Neznajka\Unit\ValueObject
 */
class TestCaseTargetData
{
    /** @var TestCaseMethodData */
    private $functionData;
    /** @var TestCaseClassData */
    private $classData;
    /** @var ReflectionMethod */
    private $methodReflection;

    /**
     * TestCaseData constructor.
     * @param TestCaseMethodData $functionData
     * @param TestCaseClassData $classData
     * @throws LogicException
     * @throws \ReflectionException
     */
    public function __construct(TestCaseMethodData $functionData, TestCaseClassData $classData)
    {
        $this->functionData = $functionData;
        $this->classData = $classData;

        $this->validate();
    }

    /**
     * @return TestCaseMethodData
     */
    public function getFunctionData(): TestCaseMethodData
    {
        return $this->functionData;
    }

    /**
     * @return TestCaseClassData
     */
    public function getClassData(): TestCaseClassData
    {
        return $this->classData;
    }

    /**
     * @return string
     */
    public function getFullFunctionName(): string
    {
        return $this->getFunctionData()->getFullFunctionName();
    }

    /**
     * @return string
     */
    public function getTargetFunction(): string
    {
        return $this->getFunctionData()->getTargetFunction();
    }

    /**
     * @return string
     */
    public function getFunctionCase(): string
    {
        return $this->getFunctionData()->getFunctionCase();
    }

    /**
     * @throws LogicException
     * @throws \ReflectionException
     */
    private function validate()
    {
        if (! $this->getClassData()->getClassReflection()->hasMethod($this->getTargetFunction())) {
            throw new LogicException("target class have no target method");
        }

        $this->methodReflection = $this->getClassData()->getClassReflection()->getMethod($this->getTargetFunction());
    }
}
