<?php
/**
 * Created by PhpStorm.
 * User: mlocmelis
 * Date: 3/1/19
 * Time: 10:22 AM
 */

namespace Tests\Neznajka\Codeception\Engine\Traits;

use AspectMock\Test;
use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\RuntimeException;
use Tests\Neznajka\Codeception\Engine\Traits\CodeceptionClass\UnitTrait;
use Tests\Neznajka\Codeception\Engine\Useful\ClassWithConstructor;

/**
 * Class MockingFeaturesTrait
 * @package Tests\Neznajka\Codeception\Engine\Traits
 */
trait MockingFeaturesTrait
{
    use UnitTrait;

    /**
     * this function call is necessary for function execution (in cases this function needs to be mocked somewhere else for same class)
     *
     * @param string $functionName
     */
    protected function addDefaultFunctionExpectation(string $functionName)
    {
        Test::func($this->getWorkingClassNameSpace(), $functionName, function () use ($functionName) {
            return call_user_func_array($functionName, func_get_args());
        });
    }

    /**
     * @param string $originalClassName
     * @return MockObject
     * @throws RuntimeException
     */
    protected function createMockExpectsNoUsage(string $originalClassName): MockObject
    {
        $result = $this->createMock($originalClassName);
        $result->expects($this->never())->method($this->anything());

        return $result;
    }

    /**
     * @param string $originalClassName
     * @param array $usedMethods
     * @return MockObject
     * @throws Exception
     */
    protected function createMockExpectsOnlyMethodUsage(string $originalClassName, array $usedMethods): MockObject
    {
        $result = $this->createPartialMock($originalClassName, $usedMethods);

        if (empty($usedMethods)) {
            throw new Exception("please define usedMethods");
        }

        return $result;
    }

    /**
     * @param string $className
     * @param array $usedMethods
     * @return MockObject
     * @throws RuntimeException
     */
    protected function createPartialAbstractMock(string $className, array $usedMethods): MockObject
    {
        $methods            = get_class_methods($className);
        $unSupportedMethods = [
            '__construct'
        ];

        $neverUsedMethods = [];
        foreach ($methods as $methodName) {
            if (! in_array($methodName, $usedMethods) && ! in_array($methodName, $unSupportedMethods)) {
                $neverUsedMethods[] = $methodName;
            }
        }

        $definedMethods = array_merge($usedMethods, $neverUsedMethods);
        $result = $this->createPartialMock($className, $definedMethods);

        foreach ($neverUsedMethods as $methodName) {
            $result->expects($this->never())->method($methodName);
        }

        return $result;
    }

    /**
     * @param array $constructorArgs
     * @return string
     * @throws Exception
     */
    protected function getDynamicConstructorTestClass(array $constructorArgs): string
    {
        $mockObject = $this->createMockExpectsOnlyMethodUsage(ClassWithConstructor::class, ['testIncomingParameter']);
        ClassWithConstructor::$mockObject = $mockObject;

        $with = [];
        $pos = 0;
        foreach ($constructorArgs as $value) {
            $with[] = [
                $pos++,
                $value
            ];
        }
        $mockObject->expects($this->exactly(count($constructorArgs)))->method('testIncomingParameter')->withConsecutive(... $with);

        return ClassWithConstructor::class;
    }
}
