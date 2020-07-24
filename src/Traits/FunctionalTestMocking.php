<?php
declare(strict_types=1);

namespace Tests\Neznajka\Codeception\Engine\Traits;

use PHPUnit\Framework\MockObject\MockBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\RuntimeException;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionException;
use Tests\Neznajka\Codeception\Engine\Objects\FunctionalTestCase;

/**
 * Class FunctionalTestMocking
 * @package Tests\Neznajka\Codeception\Engine\Traits
 *
 */
trait FunctionalTestMocking
{
    use MockingFeaturesTrait;

    /** @var TestCase */
    protected $unitTestCase;

    /** proxy to static places */
    protected function anything()
    {
        return TestCase::anything();
    }

    protected function any()
    {
        return TestCase::any();
    }

    protected function exactly(int $count)
    {
        return TestCase::exactly($count);
    }

    protected function never()
    {
        return TestCase::never();
    }

    protected function once()
    {
        return TestCase::once();
    }
    /** end of proxy to static places */

    /**
     * Returns a test double for the specified class.
     *
     * @param string $originalClassName
     *
     * @return MockObject
     *
     * @throws RuntimeException
     */
    protected function createMock($originalClassName)
    {
        return $this->getMockBuilder($originalClassName)
            ->disableOriginalConstructor()
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->getMock();
    }

    /**
     * Returns a configured test double for the specified class.
     *
     * @param string $originalClassName
     * @param array  $configuration
     *
     * @return MockObject
     * @throws RuntimeException
     */
    protected function createConfiguredMock($originalClassName, array $configuration)
    {
        $o = $this->createMock($originalClassName);

        foreach ($configuration as $method => $return) {
            $o->method($method)->willReturn($return);
        }

        return $o;
    }

    /**
     * Returns a partial test double for the specified class.
     *
     * @param string   $originalClassName
     * @param string[] $methods
     *
     * @return MockObject
     *
     * @throws ReflectionException
     */
    protected function createPartialMock($originalClassName, array $methods)
    {
        $existingMethods = [];
        $magicMethods = [];
        $notUsedMethods = [];

        $reflection = new ReflectionClass($originalClassName);
        foreach ($methods as $method) {
            if ($reflection->hasMethod($method)) {
                $existingMethods[] = $method;
            } else {
                $magicMethods[] = $method;
            }
        }

        foreach ($reflection->getMethods() as $method) {
            $methodName = $method->getName();
            if ($method->getDeclaringClass()->getName() != $originalClassName) {
                continue;
            }

            if (in_array($methodName, $methods)) {
                continue;
            }

            $notUsedMethods[] = $methodName;
            $existingMethods[] = $methodName;
        }

        $mockBuilder = $this->getMockBuilder($originalClassName)
            ->disableOriginalConstructor()
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes();

        if (count($magicMethods)) {
            $mockBuilder->addMethods($existingMethods);
        }

        if (count($existingMethods)) {
            $mockBuilder->onlyMethods($existingMethods);
        }

        $result = $mockBuilder->getMock();
        foreach ($notUsedMethods as $method) {
            $result->expects($this->never())->method($method);
        }

        return $result;
    }

    /**
     * Returns a test proxy for the specified class.
     *
     * @param string $originalClassName
     * @param array  $constructorArguments
     *
     * @return MockObject
     *
     * @throws RuntimeException
     */
    protected function createTestProxy($originalClassName, array $constructorArguments = [])
    {
        return $this->getMockBuilder($originalClassName)
            ->setConstructorArgs($constructorArguments)
            ->enableProxyingToOriginalMethods()
            ->getMock();
    }

    /**
     * Returns a builder object to create mock objects using a fluent interface.
     *
     * @param string|string[] $className
     *
     * @return MockBuilder
     */
    public function getMockBuilder($className)
    {
        if (empty($this->unitTestCase)) {
            $this->unitTestCase = new FunctionalTestCase(get_class($this));
        }

        return new MockBuilder($this->unitTestCase, $className);
    }
}
