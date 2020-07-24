<?php /** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpUnhandledExceptionInspection */

/**
 * Created by PhpStorm.
 * User: neznajka
 * Date: 19.24.3
 * Time: 14:51
 */

namespace Tests\TestsEngine\unit\Service;


use AspectMock\Test;
use LogicException;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpNamespace;
use Nette\PhpGenerator\Property;
use Nette\PhpGenerator\Method;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\RuntimeException;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use Tests\Neznajka\Codeception\Engine\Abstraction\AbstractSimpleCodeceptionTest;
use Tests\Neznajka\Codeception\Engine\Service\ClassProxyProvider;

/**
 * Class ClassProxyProviderTest
 * @package Tests\TestsEngine\unit\Service
 * @method MockObject|ClassProxyProvider getWorkingClass(...$mockedMethods)
 * @method MockObject|ClassProxyProvider getWorkingClassPrivateMock(...$mockedMethods)
 */
class ClassProxyProviderTest extends AbstractSimpleCodeceptionTest
{
    const UNIT_TEST_NAME_SPACE = "unit_test";

    public function test___construct()
    {
        $this->setKeepExistingCodeFunctions(
            [
                '__construct',
                'getReflectionClass',
            ]
        );
        $this->wantToTestThisMethod();
        $workingClass = $this->getWorkingClass();

        $reflectionClassMock = $this->createMockExpectsNoUsage(ReflectionClass::class);

        $this->runConstructorTest($workingClass, $reflectionClassMock);

        $this->assertSame($reflectionClassMock, $this->runNotPublicMethod($workingClass, 'getReflectionClass'));

    }

    public function test_createTestClass_case_class_exists()
    {
        $this->wantToTestThisMethod();
        $workingClass = $this->getWorkingClassPrivateMock('getReflectionClass');

        $classNameMock = $this->getString();
        $testClassName = $classNameMock . ClassProxyProvider::CLASS_AFFIX;
        $class = $this->getClass($testClassName);
        $classReflectionMock = $this->createMockExpectsOnlyMethodUsage(ReflectionClass::class, ['getName']);
        $expectingResult = new ReflectionClass($class->getName());

        $class_existsMock = Test::func($this->getWorkingClassNameSpace(), 'class_exists', true);

        $classReflectionMock->expects($this->once())
            ->method('getName')
            ->with()
            ->willReturn('\\' . $class->getNamespaceName() . '\\' . $classNameMock);

        $workingClass->expects($this->once())->method('getReflectionClass')->with()->willReturn($classReflectionMock);

        $result = $workingClass->createTestClass();
        $this->assertEquals($expectingResult, $result);

        $class_existsMock->verifyInvokedMultipleTimes(2, ['\\' . $class->getName()]);
    }

    public function test_createTestClass_case_class_create_success()
    {
        $this->wantToTestThisMethod();
        $workingClass = $this->getWorkingClassPrivateMock('getReflectionClass', 'createClassWithProtectedMethods');

        $classNameMock = $this->getString();
        $testClassName = $classNameMock . ClassProxyProvider::CLASS_AFFIX;
        $class = $this->getClass($testClassName);
        $classReflectionMock = $this->createMockExpectsOnlyMethodUsage(ReflectionClass::class, ['getName']);
        $expectingResult = new ReflectionClass($class->getName());

        $class_existsMock = Test::func(
            $this->getWorkingClassNameSpace(),
            'class_exists',
            function () {
                static $counter = 0;

                return (bool)$counter++;
            }
        );

        $classReflectionMock->expects($this->once())
            ->method('getName')
            ->with()
            ->willReturn('\\' . $class->getNamespaceName() . '\\' . $classNameMock);

        $workingClass->expects($this->once())->method('getReflectionClass')->with()->willReturn($classReflectionMock);
        $workingClass->expects($this->once())->method('createClassWithProtectedMethods')->with();

        $result = $workingClass->createTestClass();
        $this->assertEquals($expectingResult, $result);

        $class_existsMock->verifyInvokedMultipleTimes(2, ['\\' . $class->getName()]);
    }

    public function test_createTestClass_case_class_not_created()
    {
        $this->wantToTestThisMethod();
        $this->expectException(LogicException::class);
        $workingClass = $this->getWorkingClass('getReflectionClass', 'createClassWithProtectedMethods');

        $classNameMock = $this->getString();
        $classReflectionMock = $this->createMockExpectsOnlyMethodUsage(ReflectionClass::class, ['getName']);

        $classReflectionMock->expects($this->once())
            ->method('getName')
            ->with()
            ->willReturn($classNameMock);

        Test::func(
            $this->getWorkingClassNameSpace(),
            'class_exists',
            false
        );

        $workingClass->expects($this->once())->method('getReflectionClass')->with()->willReturn($classReflectionMock);
        $workingClass->expects($this->once())->method('createClassWithProtectedMethods')->with();

        $workingClass->createTestClass();

    }

    public function test_createTestTrait_case_class_exists()
    {
        $this->wantToTestThisMethod();
        $workingClass = $this->getWorkingClass('getReflectionClass');

        $classNameMock = $this->getString();
        $testClassName = $classNameMock . ClassProxyProvider::TRAIT_AFFIX;
        $class = $this->getClass($testClassName);
        $classReflectionMock = $this->createMockExpectsOnlyMethodUsage(ReflectionClass::class, ['getName']);
        $expectingResult = new ReflectionClass($class->getName());

        $class_existsMock = Test::func($this->getWorkingClassNameSpace(), 'class_exists', true);

        $classReflectionMock->expects($this->once())
            ->method('getName')
            ->with()
            ->willReturn('\\' . $class->getNamespaceName() . '\\' . $classNameMock);

        $workingClass->expects($this->once())->method('getReflectionClass')->with()->willReturn($classReflectionMock);

        $result = $workingClass->createTestTrait();
        $this->assertEquals($expectingResult, $result);

        $class_existsMock->verifyInvokedOnce(['\\' . $class->getName()]);
    }

    public function test_createTestTrait_case_create_class()
    {
        $this->wantToTestThisMethod();
        $workingClass = $this->getWorkingClass('getReflectionClass', 'createTestTraitClass');

        $classNameMock = $this->getString();
        $testClassName = $classNameMock . ClassProxyProvider::TRAIT_AFFIX;
        $class = $this->getClass($testClassName);
        $classReflectionMock = $this->createMockExpectsOnlyMethodUsage(ReflectionClass::class, ['getName']);
        $expectingResult = new ReflectionClass($class->getName());

        $class_existsMock = Test::func($this->getWorkingClassNameSpace(), 'class_exists', false);

        $classReflectionMock->expects($this->once())
            ->method('getName')
            ->with()
            ->willReturn('\\' . $class->getNamespaceName() . '\\' . $classNameMock);

        $workingClass->expects($this->once())->method('getReflectionClass')->with()->willReturn($classReflectionMock);
        $workingClass->expects($this->once())->method('createTestTraitClass')->with();

        $result = $workingClass->createTestTrait();
        $this->assertEquals($expectingResult, $result);

        $class_existsMock->verifyInvokedOnce(['\\' . $class->getName()]);
    }

//    public function test_createClassWithProtectedMethods()
//    {
//        $this->wantToTestThisMethod();
//        $workingClass = $this->getWorkingClass(
//            'getCurrentClass',
//            'createFinalClass',
//            'prepareClassAttributes',
//            'addUseStatements',
//            'addMethods',
//            'addProperties',
//            'defineClass'
//        );
//
//        $classMock = $this->createMockExpectsNoUsage(ClassType::class);
//        $finalClassMock = $this->createMockExpectsNoUsage(ClassType::class);
//
//        $workingClass->expects($this->once())->method('getCurrentClass')->with()->willReturn($classMock);
//        $workingClass->expects($this->once())->method('createFinalClass')->with()->willReturn($finalClassMock);
//        $workingClass->expects($this->once())->method('prepareClassAttributes')->with($classMock, $finalClassMock);
//        $workingClass->expects($this->once())->method('addUseStatements')->with($classMock, $finalClassMock);
//        $workingClass->expects($this->once())->method('addMethods')->with($classMock, $finalClassMock);
//        $workingClass->expects($this->once())->method('addProperties')->with($classMock, $finalClassMock);
//        $workingClass->expects($this->once())->method('defineClass')->with();
//
//        $this->runNotPublicMethod($workingClass, $this->getTestingMethodName());
//    }

//    public function test_createTestTraitClass()
//    {
//        $this->wantToTestThisMethod();
//        $workingClass = $this->getWorkingClass(
//            'getReflectionClass',
//            'defineClass',
//            'createNewClass'
//        );
//
//        $testClassNameMock = $this->getString();
//        $shortNameMock = $this->getString();
//
//        $classMock = $this->createMockExpectsOnlyMethodUsage(
//            ClassType::class,
//            [
//                'setAbstract',
//                'addTrait',
//            ]
//        );
//
//        $reflectionClassMock = $this->createMockExpectsOnlyMethodUsage(
//            ReflectionClass::class,
//            [
//                'getShortName',
//            ]
//        );
//
//        $classMock->expects($this->once())->method('setAbstract')->with(true);
//        $classMock->expects($this->once())->method('addTrait')->with($shortNameMock);
//
//        $reflectionClassMock->expects($this->once())->method('getShortName')->with()->willReturn($shortNameMock);
//
//        $workingClass->expects($this->once())->method('getReflectionClass')->with()->willReturn($reflectionClassMock);
//        $workingClass->expects($this->once())->method('defineClass')->with();
//        $workingClass->expects($this->once())->method('createNewClass')->willReturn($classMock);
//
//        $this->runNotPublicMethod($workingClass, $this->getTestingMethodName(), $testClassNameMock);
//    }

//    public function test_addProperties()
//    {
//        $this->wantToTestThisMethod();
//        $workingClass = $this->getWorkingClass(
//            'getReflectionClass'
//        );
//
//        $reflectionClassMock = $this->createMockExpectsOnlyMethodUsage(ReflectionClass::class, ['isTrait']);
//
//        $classMock = $this->createMockExpectsOnlyMethodUsage(ClassType::class, ['getProperties']);
//        $finalClassMock = $this->createMockExpectsOnlyMethodUsage(ClassType::class, ['setProperties']);
//
//        /** @var MockObject[] $propertyMocks */
//        $propertyMocks = [];
//        $propertyMocks[] = $this->createMockExpectsOnlyMethodUsage(Property::class, ['getVisibility']);
//        $propertyMocks[] = $this->createMockExpectsOnlyMethodUsage(Property::class, ['getVisibility', 'setVisibility']);
//        $propertyMocks[] = $this->createMockExpectsOnlyMethodUsage(Property::class, ['getVisibility']);
//
//        $propertyMocks[0]->expects($this->once())->method('getVisibility')
//            ->with()->willReturn($this->getString());
//        $propertyMocks[2]->expects($this->once())->method('getVisibility')
//            ->with()->willReturn($this->getString());
//        $propertyMocks[1]->expects($this->once())->method('getVisibility')
//            ->with()->willReturn(ClassProxyProvider::REPLACING_VISIBILITY);
//        $propertyMocks[1]->expects($this->once())->method('setVisibility')
//            ->with(ClassProxyProvider::NEW_VISIBILITY);
//
//        $classMock->expects($this->once())->method('getProperties')->with()->willReturn($propertyMocks);
//
//        $reflectionClassMock->expects($this->exactly(1))->method('isTrait')->willReturn(false);
//
//        $finalClassMock->expects($this->exactly(1))->method('setProperties')
//            ->with($propertyMocks);
//
//        $workingClass->expects($this->once())->method('getReflectionClass')->with()->willReturn($reflectionClassMock);
//
//        $this->runNotPublicMethod($workingClass, $this->getTestingMethodName(), $classMock, $finalClassMock);
//    }

//    /**
//     * @dataProvider prepareClassAttributesDataProvider
//     * @param bool $isAbstract
//     * @param bool $isTrait
//     * @throws Exception
//     * @throws LogicException
//     * @throws ReflectionException
//     * @throws RuntimeException
//     */
//    public function test_prepareClassAttributes(bool $isAbstract, bool $isTrait)
//    {
//        $this->wantToTestThisMethod();
//        $workingClass = $this->getWorkingClass('getReflectionClass');
//
//        $nameMock = $this->getString();
//
//        $reflectionClassMock = $this->createMockExpectsOnlyMethodUsage(ReflectionClass::class, ['isTrait', 'getName']);
//        $classMock = $this->createMockExpectsOnlyMethodUsage(
//            ClassType::class,
//            ['isAbstract', 'getName']
//        );
//        $finalClassMock = $this->createMockExpectsOnlyMethodUsage(
//            ClassType::class,
//            ['setAbstract', 'addTrait', 'setExtends']
//        );
//
//        $classMock->expects($this->once())->method('isAbstract')->with()->willReturn($isAbstract);
//
//        $finalClassMock->expects($this->once())->method('setAbstract')->with(
//            $isAbstract || $isTrait
//        );
//
//        if ($isTrait) {
//            $finalClassMock->expects($this->once())->method('addTrait')->with()->willReturnSelf();
//            $finalClassMock->expects($this->never())->method('setExtends');
//            $classMock->expects($this->once())->method('getName')->with()->willReturn($nameMock);
//            $reflectionClassMock->expects($this->never())->method('getName');
//        } else {
//            $finalClassMock->expects($this->once())->method('setExtends')->with($nameMock)->willReturnSelf();
//            $finalClassMock->expects($this->never())->method('addTrait');
//            $reflectionClassMock->expects($this->once())->method('getName')->with()->willReturn($nameMock);
//            $classMock->expects($this->never())->method('getName');
//        }
//
//        $reflectionClassMock->expects($this->exactly($isAbstract ? 1 : 2))->method('isTrait')->with()->willReturn($isTrait);
//        $workingClass->expects($this->once())->method('getReflectionClass')->with()->willReturn($reflectionClassMock);
//
//        $this->runNotPublicMethod($workingClass, $this->getTestingMethodName(), $classMock, $finalClassMock);
//    }
//
//    /**
//     *
//     */
//    public function prepareClassAttributesDataProvider(): array
//    {
//        return [
//            'case_trait' => ['isAbstract' => false, 'isTrait' => true],
//            'case_abstract' => ['isAbstract' => true, 'isTrait' => false],
//            'case_class' => ['isAbstract' => false, 'isTrait' => false],
//        ];
//    }

    public function test_createFinalClass()
    {
        $this->wantToTestThisMethod();
        $workingClass = $this->getWorkingClass('getReflectionClass');

        $reflectionClassMock =
            $this->createMockExpectsOnlyMethodUsage(ReflectionClass::class, ['getShortName', 'getNamespaceName']);
        $shortNameMock = $this->getString();
        $classNameMock = $shortNameMock . ClassProxyProvider::CLASS_AFFIX;
        $namespaceMock = $this->getString();

        $reflectionClassMock->expects($this->once())->method('getShortName')->with()->willReturn($shortNameMock);
        $reflectionClassMock->expects($this->once())->method('getNamespaceName')->with()->willReturn($namespaceMock);

        $workingClass->expects($this->once())->method('getReflectionClass')->with()->willReturn($reflectionClassMock);

        $expectingResult = new ClassType($classNameMock, new PhpNamespace($namespaceMock));
        Test::cleanInvocations();
        $result = $this->runNotPublicMethod($workingClass, $this->getTestingMethodName());

        $this->assertEquals($expectingResult, $result);
    }

//    public function test_getCurrentClass()
//    {
//        //        $result = ClassType::from(
//        //            $this->getReflectionClass()->getName()
//        //        );
//        //
//        //        foreach ($this->getReflectionClass()->getMethods() as $method) {
//        //            $methodName = $method->getName();
//        //            if (! $result->hasMethod($methodName)) {
//        //                continue;
//        //            }
//        //
//        //            $result->getMethod($methodName)->setBody(
//        //                $this->extractBody($method)
//        //            );
//        //        }
//        //
//        //        return $result;
//        $this->wantToTestThisMethod();
//        $workingClass = $this->getWorkingClass('getReflectionClass', 'extractBody');
//
//        $classNameMock = $this->getString();
//        $expectingResult = $this->createPartialMock(ClassType::class, ['getMethod', 'hasMethod']);
//
//        $ClassTypeMock = Test::double(ClassType::class, ['from' => $expectingResult]);
//        $reflectionMethodMocks = [];
//
//        $reflectionMethodMocks[] = $this->createPartialAbstractMock(ReflectionMethod::class, ['getName']);
//        $reflectionMethodMocks[] = $this->createPartialAbstractMock(ReflectionMethod::class, ['getName']);
//        $reflectionMethodMocks[] = $this->createPartialAbstractMock(ReflectionMethod::class, ['getName']);
//
//        /** @var Method[]|MockObject[] $netteMethodMocks */
//        $netteMethodMocks = [];
//
//        $netteMethodMocks[] = $this->createPartialAbstractMock(Method::class, ['setBody']);
//        $netteMethodMocks[] = $this->createMockExpectsNoUsage(Method::class);
//        $netteMethodMocks[] = $this->createPartialAbstractMock(Method::class, ['setBody']);
//
//        $reflectionClassMock = $this->createPartialAbstractMock(ReflectionClass::class, ['getMethods', 'getName']);
//        $workingClass->expects($this->once())->method('getReflectionClass')->willReturn($reflectionClassMock);
//        $method1BodyMock = $this->getString();
//        $method1NameMock = $this->getString();
//        $method2BodyMock = $this->getString();
//        $method2NameMock = $this->getString();
//        $emptyMethodNameMock = $this->getString();
//        $workingClass->expects($this->exactly(2))->method('extractBody')->willReturnOnConsecutiveCalls($method1BodyMock, $method2BodyMock);
//
//        $reflectionClassMock->expects($this->once())->method('getMethods')->willReturn($reflectionMethodMocks);
//        $reflectionClassMock->expects($this->once())->method('getName')->willReturn($classNameMock);
//
//        $expectingResult->expects($this->exactly(3))
//            ->method('hasMethod')
//            ->withConsecutive([$method1NameMock],[$emptyMethodNameMock], [$method2NameMock])
//            ->willReturnOnConsecutiveCalls(true, false, true);
//
//        $expectingResult->expects($this->exactly(2))
//            ->method('getMethod')
//            ->withConsecutive([$method1NameMock], [$method2NameMock])
//            ->willReturnOnConsecutiveCalls($netteMethodMocks[0], $netteMethodMocks[2]);
//
//        $reflectionMethodMocks[0]->expects($this->once())->method('getName')->willReturn($method1NameMock);
//        $reflectionMethodMocks[1]->expects($this->once())->method('getName')->willReturn($emptyMethodNameMock);
//        $reflectionMethodMocks[2]->expects($this->once())->method('getName')->willReturn($method2NameMock);
//
//        $netteMethodMocks[0]->expects($this->once())->method('setBody')->with($method1BodyMock);
//        $netteMethodMocks[2]->expects($this->once())->method('setBody')->with($method2BodyMock);
//
//        $result = $this->runNotPublicMethod($workingClass, $this->getTestingMethodName());
//        $this->assertEquals($expectingResult, $result);
//
//        $ClassTypeMock->verifyInvokedOnce('from', [$classNameMock]);
//    }
//
//    public function test_addUseStatements()
//    {
//        $this->wantToTestThisMethod();
//        $workingClass = $this->getWorkingClass();
//
//        $classMock = $this->createMockExpectsOnlyMethodUsage(ClassType::class, ['getTraits']);
//        $finalClassMock = $this->createMockExpectsOnlyMethodUsage(ClassType::class, ['addTrait']);
//
//        $useStatementMocks = $this->getArray();
//
//        $classMock->expects($this->once())->method('getTraits')->with()->willReturn($useStatementMocks);
//        $finalClassMock->expects($this->exactly(count($useStatementMocks)))->method('addTrait')
//            ->withConsecutive(... $this->getConsucativeCallsFromArray($useStatementMocks));
//
//        $this->runNotPublicMethod($workingClass, $this->getTestingMethodName(), $classMock, $finalClassMock);
//    }
//
//    public function test_addMethods()
//    {
//        $this->wantToTestThisMethod();
//        $workingClass = $this->getWorkingClass('assignNewMethod');
//
//        $classMock = $this->createMockExpectsOnlyMethodUsage(ClassType::class, ['getMethods']);
//        $finalClassMock = $this->createMockExpectsOnlyMethodUsage(ClassType::class, ['setMethods']);
//
//        /** @var Method[]|MockObject[] $methodMocks */
//        $methodMocks = [];
//
//        $methodMocks[] = $this->createPartialAbstractMock(Method::class, ['getVisibility']);
//        $methodMocks[] = $this->createPartialAbstractMock(Method::class, ['getVisibility', 'setVisibility']);
//        $methodMocks[] = $this->createPartialAbstractMock(Method::class, ['getVisibility']);
//
//        $classMock->expects($this->once())->method('getMethods')->with()->willReturn($methodMocks);
//
//        $methodMocks[0]->expects($this->once())->method('getVisibility')->willReturn($this->getString());
//        $methodMocks[1]->expects($this->once())->method('getVisibility')->willReturn(ClassProxyProvider::REPLACING_VISIBILITY);
//        $methodMocks[1]->expects($this->once())->method('setVisibility')->with(ClassProxyProvider::NEW_VISIBILITY);
//        $methodMocks[2]->expects($this->once())->method('getVisibility')->willReturn($this->getString());
//
//        $finalClassMock->expects($this->once())->method('setMethods')->with($methodMocks);
//
//
//        $this->runNotPublicMethod($workingClass, $this->getTestingMethodName(), $classMock, $finalClassMock);
//    }

    /**
     * @return string
     */
    protected function getWorkingClassName(): string
    {
        return ClassProxyProvider::class;
    }
}
