<?php /** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */
declare(strict_types=1);

namespace Tests\TestsEngine\functional\Traits;

use Codeception\Example;
use FunctionalTester;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\MockObject\MockBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use stdClass;
use Tests\Neznajka\Codeception\Engine\Abstraction\AbstractFunctionalSymfonyCodeceptionTest;
use Tests\Neznajka\Codeception\Engine\Objects\CallableAction;
use Tests\Neznajka\Codeception\Engine\Objects\MysqlResultMock;

/**
 * Class FunctionalTestMockingCest
 * @package Tests\TestsEngine\functional\Traits
 */
class FunctionalTestMockingCest extends AbstractFunctionalSymfonyCodeceptionTest
{

    public function testAnything(FunctionalTester $I)
    {
        $I->assertEquals(
            TestCase::anything(),
            $this->anything()
        );
    }

    public function testAny(FunctionalTester $I)
    {
        $I->assertEquals(
            TestCase::any(),
            $this->any()
        );
    }

    public function testExactly(FunctionalTester $I)
    {
        $count = $this->getInt();
        $I->assertEquals(
            TestCase::exactly($count),
            $this->exactly($count)
        );
    }

    public function testNever(FunctionalTester $I)
    {
        $I->assertEquals(
            TestCase::never(),
            $this->never()
        );
    }

    public function testOnce(FunctionalTester $I)
    {
        $I->assertEquals(
            TestCase::once(),
            $this->once()
        );
    }

    /**
     * @dataProvider getClassTestMockNamesProvider
     * @param FunctionalTester $I
     * @param Example $example
     * @throws AssertionFailedError
     */
    public function testCreateMock(FunctionalTester $I, Example $example)
    {
        $className = $example->offsetGet('className');
        $mock      = $this->createMock($className);

        $I->assertInstanceOf(MockObject::class, $mock);
        $I->assertInstanceOf($className, $mock);
    }

    /**
     * @dataProvider getClassTestMockNamesProvider
     * @param FunctionalTester $I
     * @param Example $example
     * @throws AssertionFailedError
     */
    public function testCreateConfiguredMock(FunctionalTester $I, Example $example)
    {
        $className         = $example->offsetGet('className');
        $configuredMethods = $example->offsetGet('configuredMethods');
        $mock              = $this->createConfiguredMock($className, $configuredMethods);

        $I->assertInstanceOf(MockObject::class, $mock);
        $I->assertInstanceOf($className, $mock);

        foreach ($configuredMethods as $methodName => $methodReturn) {
            $I->assertSame($methodReturn, $this->callMockedMethod($methodName, $mock));
        }

        foreach ($example->offsetGet('notConfiguredMethods') as $methodName => $methodOkayResponse) {
            $I->assertSame(null, $this->callMockedMethod($methodName, $mock));
        }
    }

    /**
     * @dataProvider getClassTestMockNamesProvider
     * @param FunctionalTester $I
     * @param Example $example
     * @throws AssertionFailedError
     */
    public function testCreatePartialMock(FunctionalTester $I, Example $example)
    {
        $className         = $example->offsetGet('className');
        $configuredMethods = $example->offsetGet('configuredMethods');
        $mock              = $this->createPartialMock($className, array_keys($configuredMethods));

        $I->assertInstanceOf(MockObject::class, $mock);
        $I->assertInstanceOf($className, $mock);

        foreach ($configuredMethods as $methodName => $methodReturn) {
            $mock->expects($this->once())->method($methodName)->willReturn($methodReturn);
            $I->assertSame($methodReturn, $this->callMockedMethod($methodName, $mock));
        }


        foreach ($example->offsetGet('notConfiguredMethods') as $methodName => $methodOkayResponse) {
            $I->assertSame($methodOkayResponse, $this->callMockedMethod($methodName, $mock));
        }
    }

    /**
     * @dataProvider getClassTestMockNamesProvider
     * @param FunctionalTester $I
     * @param Example $example
     * @throws AssertionFailedError
     */
    public function testCreateTestProxy(FunctionalTester $I, Example $example)
    {
        $className = $example->offsetGet('className');
        $mock      = $this->createTestProxy($className, $example->offsetGet('constructorParameters'));

        $I->assertInstanceOf(MockObject::class, $mock);
        $I->assertInstanceOf($className, $mock);

        foreach ($example->offsetGet('notConfiguredMethods') as $methodName => $methodOkayResponse) {
            $I->assertSame($methodOkayResponse, $this->callMockedMethod($methodName, $mock));
        }
    }

    /**
     * @dataProvider getClassTestMockNamesProvider
     * @param FunctionalTester $I
     * @param Example $example
     * @throws AssertionFailedError
     */
    public function testGetMockBuilder(FunctionalTester $I, Example $example)
    {
        $className   = $example->offsetGet('className');
        $mockBuilder = $this->getMockBuilder($className);

        $I->assertEquals(new MockBuilder($this->unitTestCase, $className), $mockBuilder);

    }

    protected function getClassTestMockNamesProvider()
    {
        return [
            [
                'className'             => CallableAction::class,
                'configuredMethods'     => ['handle' => $this->getString()],
                'notConfiguredMethods'  => ['resultTrue' => true],
                'constructorParameters' => [
                    function () {
                    },
                ],
            ],
            [
                'className'             => MysqlResultMock::class,
                'configuredMethods'     => ['fetch_assoc' => null, 'fetch_object' => new stdClass()],
                'notConfiguredMethods'  => ['valid' => false],
                'constructorParameters' => [null],
            ],
        ];
    }

    /**
     * @param $methodName
     * @param MockObject $mock
     * @return mixed
     */
    protected function callMockedMethod($methodName, MockObject $mock)
    {
        if ($methodName === 'handle') {
            return $mock->$methodName($this->createMockExpectsNoUsage(FunctionalTester::class));
        }

        return $mock->$methodName();
    }
}
