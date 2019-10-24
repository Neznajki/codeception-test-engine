<?php
declare(strict_types=1);

namespace Tests\Neznajka\Codeception\Engine\Traits;

use PHPUnit\Framework\MockObject\MockBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
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
     *
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
     */
    protected function createPartialMock($originalClassName, array $methods)
    {
        return $this->getMockBuilder($originalClassName)
            ->disableOriginalConstructor()
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->setMethods(empty($methods) ? null : $methods)
            ->getMock();
    }

    /**
     * Returns a test proxy for the specified class.
     *
     * @param string $originalClassName
     * @param array  $constructorArguments
     *
     * @return MockObject
     *
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
