<?php /** @noinspection PhpUnhandledExceptionInspection */

/**
 * Created by PhpStorm.
 * User: mlocmelis
 * Date: 3/6/19
 * Time: 11:32 AM
 */

namespace Tests\TestsEngine\unit\Useful;

use PHPUnit\Framework\MockObject\MockObject;
use Tests\Neznajka\Unit\Abstraction\AbstractSimpleCodeceptionTest;
use Tests\Neznajka\Unit\Useful\ClassWithConstructor;

/**
 * Class ClassWithConstructorTest
 * @package Tests\TestsEngine\unit\Useful
 * @method MockObject|ClassWithConstructor getWorkingClass(... $mockedMethods)
 */
class ClassWithConstructorTest extends AbstractSimpleCodeceptionTest
{
    public function test_example()
    {
        $constructorArgs = $this->getArray();
        $className       = $this->getDynamicConstructorTestClass($constructorArgs);
        new $className(... $constructorArgs);

        //test repeat should have exception
        $this->expectException(\LogicException::class);
        new $className(... $constructorArgs);
    }

    public function test_testIncomingParameter_case_success()
    {
        $this->wantToTestThisMethod();

        $this->setKeepExistingCodeFunctions([
            $this->getTestingMethodName(),
            'setMockObject'
        ]);
        $workingClass = $this->getWorkingClass();
        /** @var MockObject|ClassWithConstructor $mockObjectMock */
        $mockObjectMock                   = $this->createMockExpectsOnlyMethodUsage(
            ClassWithConstructor::class,
            ['testIncomingParameter']
        );

        $workingClass->setMockObject($mockObjectMock);
        $posMock                          = $this->getInt();
        $valueMock                        = $this->anything();
        $mockObjectMock->expects($this->once())->method('testIncomingParameter')->with($posMock, $valueMock);

        $this->runNotPublicMethod($workingClass, $this->getTestingMethodName(), $posMock, $valueMock);
        $workingClass->setMockObject(null);//just for this case as working class is emulated
    }

    public function test_testIncomingParameter_case_exception_mock_object_not_defined()
    {
        $this->wantToTestThisMethod();
        $workingClass = $this->getWorkingClass();
        $this->expectException(\LogicException::class);

        ClassWithConstructor::$mockObject = null;
        $posMock                          = $this->getInt();
        $valueMock                        = $this->anything();

        $this->runNotPublicMethod($workingClass, $this->getTestingMethodName(), $posMock, $valueMock);
    }

    /**
     * @return string
     */
    protected function getWorkingClassName(): string
    {
        return ClassWithConstructor::class;
    }
}
