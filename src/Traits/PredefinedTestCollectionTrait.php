<?php
/**
 * Created by PhpStorm.
 * User: mlocmelis
 * Date: 3/1/19
 * Time: 11:07 AM
 */

namespace Tests\Neznajka\Codeception\Engine\Traits;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\RuntimeException;
use ReflectionClass;
use ReflectionException;
use Tests\Neznajka\Codeception\Engine\Traits\CodeceptionClass\UnitTrait;
use TypeError;
use UnitTester;

/**
 * Class PredefinedTestCollectionTrait
 * @package Tests\Neznajka\Codeception\Engine\Traits
 *
 * @uses \Tests\Neznajka\Codeception\Engine\Traits\CommonAbstractionTrait
 * @method UnitTester getTester();
 */
trait PredefinedTestCollectionTrait
{
    use UnitTrait;

    /**
     * @param string $traitName
     */
    protected function checkTraitUsage(string $traitName)
    {
        $traits = class_uses($this->getWorkingClassName());

        $this->assertArrayHasKey($traitName, $traits, "trait {$traitName} should be used inside {$this->getWorkingClassName()}");
    }

    /**
     * @param string $functionName
     * @param mixed ...$arguments
     * @throws RuntimeException
     * @throws ReflectionException
     */
    protected function runEmptyFunctionTest(string $functionName, ... $arguments)
    {
        $this->getTester()->wantTo("make sure {$functionName} contains no logic");

        $class = $this->getMockBuilder($this->getWorkingClassName())
            ->setMethodsExcept([$functionName])
            ->disableOriginalConstructor()
            ->enableProxyingToOriginalMethods()
            ->getMockForAbstractClass();

        $class->expects($this->never())
            ->method($this->anything());

        $result = $this->runNotPublicMethod($class, $functionName, ...$arguments);
        $this->assertNull($result);
    }

    /**
     * @param MockObject $mockedClass
     * @param mixed ...$parameterN
     * @throws ReflectionException
     */
    protected function runConstructorTest(MockObject $mockedClass, ... $parameterN)
    {
        $reflectedClass = new ReflectionClass(get_class($mockedClass));
        $constructor    = $reflectedClass->getConstructor();
        if (! $constructor->isPublic()) {
            $constructor->setAccessible(true);
        }
        call_user_func_array([$constructor, 'invoke'], func_get_args());
    }

    /**
     * @param object $object
     * @param string $methodName
     * @param mixed ...$arguments
     * @return mixed
     * @throws ReflectionException
     */
    protected function runNotPublicMethod($object, string $methodName, ... $arguments)
    {
        $reflection = new ReflectionClass($object);
        $method     = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $arguments);
    }


    /**
     * @param string $parameterName
     * @param $parameterValue
     * @throws ReflectionException
     */
    protected function executeGetterSetterTest(string $parameterName, $parameterValue)
    {
        $callingFunctionName = ucfirst(preg_replace('/^\\$/', '', $parameterName));
        $prefix              = 'get';
        if (is_bool($parameterValue)) {
            $prefix = 'is';
        }
        $this->wantToTestMethod($parameterName . ' getter and setter');
        $this->setKeepExistingCodeFunctions(
            [
                $prefix . ucfirst($parameterName),
                'set' . ucfirst($parameterName),
            ]
        );
        $class = $this->getWorkingClass();

        $this->runNotPublicMethod($class, 'set' . $callingFunctionName, $parameterValue);
        $expectingResult = $parameterValue;

        $result = $this->runNotPublicMethod($class, $prefix . $callingFunctionName, $parameterValue);

        $this->assertSame($expectingResult, $result);
    }

    /**
     * @param string $parameterName
     * @param $parameterValue
     * @throws ReflectionException
     */
    protected function executeGetterTest(string $parameterName, $parameterValue)
    {
        $this->wantToTestThisMethod();
        $workingClass = $this->getWorkingClass();

        $this->setNotPublicValue($workingClass, $parameterName, $parameterValue);

        $result = $this->runNotPublicMethod($workingClass, $this->getTestingMethodName());
        $this->assertSame($parameterValue, $result);
    }

    /**
     * @param string $parameterName
     * @param mixed|null $parameterValue
     * @throws ReflectionException
     */
    protected function executeStrictTest(string $parameterName, $parameterValue = null)
    {
        $this->wantToTestMethod($parameterName . ' strict type definition');
        $this->expectException(TypeError::class);
        $workingClass = $this->getWorkingClass();

        $this->setNotPublicValue($workingClass, $parameterName, $parameterValue);

        $this->runNotPublicMethod($workingClass, $this->getTestingMethodName());
    }
}
