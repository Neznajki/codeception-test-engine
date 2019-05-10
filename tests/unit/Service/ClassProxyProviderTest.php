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
use gossi\codegen\generator\CodeGenerator;
use gossi\codegen\model\AbstractPhpStruct;
use gossi\codegen\model\PhpClass;
use gossi\codegen\model\PhpMethod;
use gossi\codegen\model\PhpProperty;
use PHPUnit\Framework\MockObject\MockObject;
use ReflectionClass;
use ReflectionMethod;
use ReflectionType;
use Tests\Neznajka\Unit\Abstraction\AbstractSimpleCodeceptionTest;
use Tests\Neznajka\Unit\Service\ClassProxyProvider;

/**
 * Class ClassProxyProviderTest
 * @package Tests\TestsEngine\unit\Service
 * @method MockObject|ClassProxyProvider getWorkingClass(... $mockedMethods)
 * @method MockObject|ClassProxyProvider getWorkingClassPrivateMock(... $mockedMethods)
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

        $classNameMock       = $this->getString();
        $testClassName       = $classNameMock . ClassProxyProvider::CLASS_AFFIX;
        $class               = $this->getClass($testClassName);
        $classReflectionMock = $this->createMockExpectsOnlyMethodUsage(ReflectionClass::class, ['getName']);
        $expectingResult     = new ReflectionClass($class->getName());

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

        $classNameMock       = $this->getString();
        $testClassName       = $classNameMock . ClassProxyProvider::CLASS_AFFIX;
        $class               = $this->getClass($testClassName);
        $classReflectionMock = $this->createMockExpectsOnlyMethodUsage(ReflectionClass::class, ['getName']);
        $expectingResult     = new ReflectionClass($class->getName());

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
        $this->expectException(\LogicException::class);
        $workingClass = $this->getWorkingClass('getReflectionClass', 'createClassWithProtectedMethods');

        $classNameMock       = $this->getString();
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

        $classNameMock       = $this->getString();
        $testClassName       = $classNameMock . ClassProxyProvider::TRAIT_AFFIX;
        $class               = $this->getClass($testClassName);
        $classReflectionMock = $this->createMockExpectsOnlyMethodUsage(ReflectionClass::class, ['getName']);
        $expectingResult     = new ReflectionClass($class->getName());

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

        $classNameMock       = $this->getString();
        $testClassName       = $classNameMock . ClassProxyProvider::TRAIT_AFFIX;
        $class               = $this->getClass($testClassName);
        $classReflectionMock = $this->createMockExpectsOnlyMethodUsage(ReflectionClass::class, ['getName']);
        $expectingResult     = new ReflectionClass($class->getName());

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

    public function test_getCodeGenerator()
    {
        $this->wantToTestThisMethod();
        $workingClass = $this->getWorkingClass();

        $expectingResult = new CodeGenerator(
            [
                'generateDocblock'        => false,
                'generateScalarTypeHints' => true,
                'generateReturnTypeHints' => true,
            ]
        );

        $result = $this->runNotPublicMethod($workingClass, $this->getTestingMethodName());
        $this->assertEquals($expectingResult, $result);
    }

    public function test_assignNewMethod_case_private()
    {
        $this->wantToTestThisMethod();
        $workingClass = $this->getWorkingClass('fixReturnType');

        $methodMock     = $this->createMockExpectsOnlyMethodUsage(PhpMethod::class, ['getVisibility', 'setVisibility']);
        $finalClassMock = $this->createMockExpectsOnlyMethodUsage(PhpClass::class, ['setMethod']);

        $methodMock->expects($this->once())->method('getVisibility')->with()->willReturn(
            ClassProxyProvider::REPLACING_VISIBILITY
        );
        $methodMock->expects($this->once())->method('setVisibility')->with(ClassProxyProvider::NEW_VISIBILITY);

        $finalClassMock->expects($this->once())->method('setMethod')->with($methodMock);

        $workingClass->expects($this->once())->method('fixReturnType')->with($methodMock);

        $this->runNotPublicMethod($workingClass, $this->getTestingMethodName(), $methodMock, $finalClassMock);
    }

    public function test_assignNewMethod_case_not_private()
    {
        $this->wantToTestThisMethod();
        $workingClass = $this->getWorkingClass('fixReturnType');

        $methodMock     = $this->createMockExpectsOnlyMethodUsage(PhpMethod::class, ['getVisibility']);
        $finalClassMock = $this->createMockExpectsOnlyMethodUsage(PhpClass::class, ['setMethod']);

        $methodMock->expects($this->once())->method('getVisibility')->with()->willReturn($this->getString());

        $finalClassMock->expects($this->once())->method('setMethod')->with($methodMock);

        $workingClass->expects($this->once())->method('fixReturnType')->with($methodMock);

        $this->runNotPublicMethod($workingClass, $this->getTestingMethodName(), $methodMock, $finalClassMock);
    }

    public function test_createClassWithProtectedMethods()
    {
        $this->wantToTestThisMethod();
        $workingClass = $this->getWorkingClass(
            'getCurrentClass',
            'createFinalClass',
            'prepareClassAttributes',
            'addUseStatements',
            'addMethods',
            'addProperties',
            'defineClass'
        );

        $classMock      = $this->createMockExpectsNoUsage(PhpClass::class);
        $finalClassMock = $this->createMockExpectsNoUsage(PhpClass::class);

        $workingClass->expects($this->once())->method('getCurrentClass')->with()->willReturn($classMock);
        $workingClass->expects($this->once())->method('createFinalClass')->with()->willReturn($finalClassMock);
        $workingClass->expects($this->once())->method('prepareClassAttributes')->with($classMock, $finalClassMock);
        $workingClass->expects($this->once())->method('addUseStatements')->with($classMock, $finalClassMock);
        $workingClass->expects($this->once())->method('addMethods')->with($classMock, $finalClassMock);
        $workingClass->expects($this->once())->method('addProperties')->with($classMock, $finalClassMock);
        $workingClass->expects($this->once())->method('defineClass')->with();

        $this->runNotPublicMethod($workingClass, $this->getTestingMethodName());
    }

    public function test_createTestTraitClass()
    {
        $this->wantToTestThisMethod();
        $workingClass = $this->getWorkingClass(
            'getReflectionClass',
            'defineClass'
        );

        $testClassNameMock = $this->getString();
        $shortNameMock     = $this->getString();
        $nameSpaceMock     = $this->getString();

        $classMock = $this->createMockExpectsOnlyMethodUsage(
            PhpClass::class,
            [
                'setAbstract',
                'setNamespace',
                'addTrait',
            ]
        );

        $phpClassMock = Test::double(PhpClass::class, ['create' => $classMock]);

        $reflectionClassMock = $this->createMockExpectsOnlyMethodUsage(
            ReflectionClass::class,
            [
                'getNamespaceName',
                'getShortName',
            ]
        );

        $classMock->expects($this->once())->method('setAbstract')->with(true);
        $classMock->expects($this->once())->method('setNamespace')->with($nameSpaceMock);
        $classMock->expects($this->once())->method('addTrait')->with($shortNameMock);

        $reflectionClassMock->expects($this->once())->method('getNamespaceName')->with()->willReturn($nameSpaceMock);
        $reflectionClassMock->expects($this->once())->method('getShortName')->with()->willReturn($shortNameMock);

        $workingClass->expects($this->once())->method('getReflectionClass')->with()->willReturn($reflectionClassMock);
        $workingClass->expects($this->once())->method('defineClass')->with();

        $this->runNotPublicMethod($workingClass, $this->getTestingMethodName(), $testClassNameMock);

        $phpClassMock->verifyInvokedOnce('create', [$testClassNameMock]);
    }

    public function test_fixReturnType_case_reflection_type()
    {
        $this->wantToTestThisMethod();
        $workingClass = $this->getWorkingClass('getReflectionClass');

        $typeMock       = $this->getString();
        $methodNameMock = $this->getString();

        $reflectionClassMock  = $this->createMockExpectsOnlyMethodUsage(ReflectionClass::class, ['getMethod']);
        $reflectionMethodMock = $this->createMockExpectsOnlyMethodUsage(ReflectionMethod::class, ['getReturnType']);
        $methodMock           = $this->createMockExpectsOnlyMethodUsage(PhpMethod::class, ['setType', 'getName']);
        $reflectionTypeMock   = $this->createMockExpectsOnlyMethodUsage(ReflectionType::class, ['__toString']);

        $reflectionClassMock->expects($this->once())
            ->method('getMethod')
            ->with($methodNameMock)
            ->willReturn($reflectionMethodMock);
        $reflectionMethodMock->expects($this->once())->method('getReturnType')->with()->willReturn($reflectionTypeMock);

        $reflectionTypeMock->expects($this->once())->method('__toString')->with()->willReturn($typeMock);

        $methodMock->expects($this->once())->method('getName')->with()->willReturn($methodNameMock);
        $methodMock->expects($this->once())->method('setType')->with($typeMock);

        $workingClass->expects($this->once())->method('getReflectionClass')->with()->willReturn($reflectionClassMock);

        $this->runNotPublicMethod($workingClass, $this->getTestingMethodName(), $methodMock);
    }

    public function test_fixReturnType_case_self()
    {
        $this->wantToTestThisMethod();
        $workingClass = $this->getWorkingClass('getReflectionClass');

        $typeMock       = 'self';
        $classNameMock  = $this->getString();
        $methodNameMock = $this->getString();

        $reflectionClassMock  = $this->createMockExpectsOnlyMethodUsage(ReflectionClass::class, ['getMethod', 'getName']);
        $reflectionMethodMock = $this->createMockExpectsOnlyMethodUsage(ReflectionMethod::class, ['getReturnType']);
        $methodMock           = $this->createMockExpectsOnlyMethodUsage(PhpMethod::class, ['setType', 'getName']);
        $reflectionTypeMock   = $this->createMockExpectsOnlyMethodUsage(ReflectionType::class, ['__toString']);

        $reflectionClassMock->expects($this->once())
            ->method('getMethod')
            ->with($methodNameMock)
            ->willReturn($reflectionMethodMock);
        $reflectionClassMock->expects($this->once())->method('getName')->with()->willReturn($classNameMock);

        $reflectionMethodMock->expects($this->once())->method('getReturnType')->with()->willReturn($reflectionTypeMock);

        $reflectionTypeMock->expects($this->once())->method('__toString')->with()->willReturn($typeMock);

        $methodMock->expects($this->once())->method('getName')->with()->willReturn($methodNameMock);
        $methodMock->expects($this->once())->method('setType')->with('\\' . $classNameMock);

        $workingClass->expects($this->once())->method('getReflectionClass')->with()->willReturn($reflectionClassMock);

        $this->runNotPublicMethod($workingClass, $this->getTestingMethodName(), $methodMock);
    }

    public function test_fixReturnType_case_no_global()
    {
        $this->wantToTestThisMethod();
        $workingClass = $this->getWorkingClassPrivateMock('getReflectionClass');

        $typeMock       = $this->getString();
        $methodNameMock = $this->getString();

        $reflectionClassMock  = $this->createMockExpectsOnlyMethodUsage(ReflectionClass::class, ['getMethod']);
        $reflectionMethodMock = $this->createMockExpectsOnlyMethodUsage(ReflectionMethod::class, ['getReturnType']);
        $methodMock           = $this->createMockExpectsOnlyMethodUsage(PhpMethod::class, ['setType', 'getName']);

        $class_existsMock     = Test::func($this->getWorkingClassNameSpace(), 'class_exists', false);
        $interface_existsMock = Test::func($this->getWorkingClassNameSpace(), 'interface_exists', true);
        $preg_matchMock       = Test::func($this->getWorkingClassNameSpace(), 'preg_match', 0);

        $reflectionClassMock->expects($this->once())
            ->method('getMethod')
            ->with($methodNameMock)
            ->willReturn($reflectionMethodMock);
        $reflectionMethodMock->expects($this->once())->method('getReturnType')->with()->willReturn($typeMock);

        $methodMock->expects($this->once())->method('getName')->with()->willReturn($methodNameMock);
        $methodMock->expects($this->once())->method('setType')->with('\\' . $typeMock);

        $workingClass->expects($this->once())->method('getReflectionClass')->with()->willReturn($reflectionClassMock);

        $this->runNotPublicMethod($workingClass, $this->getTestingMethodName(), $methodMock);

        $class_existsMock->verifyInvokedOnce([$typeMock]);
        $interface_existsMock->verifyInvokedOnce([$typeMock]);
        $preg_matchMock->verifyInvokedOnce(['/^\\\\/', $typeMock]);
    }

    public function test_addProperties()
    {
        $this->wantToTestThisMethod();
        $workingClass = $this->getWorkingClass(
            'getReflectionClass'
        );

        $reflectionClassMock = $this->createMockExpectsOnlyMethodUsage(ReflectionClass::class, ['isTrait']);

        $classMock      = $this->createMockExpectsOnlyMethodUsage(PhpClass::class, ['getProperties']);
        $finalClassMock = $this->createMockExpectsOnlyMethodUsage(PhpClass::class, ['setProperty']);

        /** @var MockObject[] $propertyMocks */
        $propertyMocks   = [];
        $propertyMocks[] = $this->createMockExpectsOnlyMethodUsage(PhpProperty::class, ['getVisibility']);
        $propertyMocks[] = $this->createMockExpectsNoUsage(PhpProperty::class);
        $propertyMocks[] = $this->createMockExpectsOnlyMethodUsage(PhpProperty::class, ['getVisibility', 'setVisibility']);

        $propertyMocks[0]->expects($this->once())->method('getVisibility')
            ->with()->willReturn($this->getString());
        $propertyMocks[2]->expects($this->once())->method('getVisibility')
            ->with()->willReturn(ClassProxyProvider::REPLACING_VISIBILITY);
        $propertyMocks[2]->expects($this->once())->method('setVisibility')
            ->with()->willReturn(ClassProxyProvider::NEW_VISIBILITY);

        $classMock->expects($this->once())->method('getProperties')->with()->willReturn($propertyMocks);

        $reflectionClassMock->expects($this->exactly(3))->method('isTrait')
            ->with()->willReturnOnConsecutiveCalls(false, true, false);

        $finalClassMock->expects($this->exactly(2))->method('setProperty')
            ->withConsecutive($propertyMocks[0], $propertyMocks[2]);

        $workingClass->expects($this->once())->method('getReflectionClass')->with()->willReturn($reflectionClassMock);

        $this->runNotPublicMethod($workingClass, $this->getTestingMethodName(), $classMock, $finalClassMock);
    }

    public function test_prepareClassAttributes_case_trait()
    {
        $this->wantToTestThisMethod();
        $workingClass = $this->getWorkingClass('getReflectionClass');

        $nameMock      = $this->getString();
        $nameSpaceMock = $this->getString();

        $reflectionClassMock = $this->createMockExpectsOnlyMethodUsage(ReflectionClass::class, ['isTrait']);
        $classMock           = $this->createMockExpectsOnlyMethodUsage(
            PhpClass::class,
            ['isAbstract', 'getName', 'getNamespace']
        );
        $finalClassMock      = $this->createMockExpectsOnlyMethodUsage(
            PhpClass::class,
            ['setAbstract', 'setNamespace', 'addTrait']
        );

        $classMock->expects($this->once())->method('isAbstract')->with()->willReturn(false);
        $classMock->expects($this->once())->method('getName')->with()->willReturn($nameMock);
        $classMock->expects($this->once())->method('getNamespace')->with()->willReturn($nameSpaceMock);

        $finalClassMock->expects($this->once())->method('setAbstract')->with(true);
        $finalClassMock->expects($this->once())->method('addTrait')->with()->willReturn($nameMock);
        $finalClassMock->expects($this->once())->method('setNamespace')->with()->willReturn($nameSpaceMock);

        $reflectionClassMock->expects($this->exactly(2))->method('isTrait')->with()->willReturn(true);
        $workingClass->expects($this->once())->method('getReflectionClass')->with()->willReturn($reflectionClassMock);

        $this->runNotPublicMethod($workingClass, $this->getTestingMethodName(), $classMock, $finalClassMock);
    }

    public function test_prepareClassAttributes_case_abstract_class()
    {
        $this->wantToTestThisMethod();
        $workingClass = $this->getWorkingClass('getReflectionClass');

        $nameMock      = $this->getString();
        $nameSpaceMock = $this->getString();

        $reflectionClassMock = $this->createMockExpectsOnlyMethodUsage(ReflectionClass::class, ['isTrait']);
        $classMock           = $this->createMockExpectsOnlyMethodUsage(
            PhpClass::class,
            ['isAbstract', 'getName', 'getNamespace']
        );
        $finalClassMock      = $this->createMockExpectsOnlyMethodUsage(
            PhpClass::class,
            ['setAbstract', 'setNamespace', 'setParentClassName']
        );

        $classMock->expects($this->once())->method('isAbstract')->with()->willReturn(true);
        $classMock->expects($this->once())->method('getName')->with()->willReturn($nameMock);
        $classMock->expects($this->once())->method('getNamespace')->with()->willReturn($nameSpaceMock);

        $finalClassMock->expects($this->once())->method('setAbstract')->with(true);
        $finalClassMock->expects($this->once())->method('setParentClassName')->with()->willReturn($nameMock);
        $finalClassMock->expects($this->once())->method('setNamespace')->with()->willReturn($nameSpaceMock);

        $reflectionClassMock->expects($this->once())->method('isTrait')->with()->willReturn(false);
        $workingClass->expects($this->once())->method('getReflectionClass')->with()->willReturn($reflectionClassMock);

        $this->runNotPublicMethod($workingClass, $this->getTestingMethodName(), $classMock, $finalClassMock);
    }

    public function test_prepareClassAttributes_case_class()
    {
        $this->wantToTestThisMethod();
        $workingClass = $this->getWorkingClass('getReflectionClass');

        $nameMock      = $this->getString();
        $nameSpaceMock = $this->getString();

        $reflectionClassMock = $this->createMockExpectsOnlyMethodUsage(ReflectionClass::class, ['isTrait']);
        $classMock           = $this->createMockExpectsOnlyMethodUsage(
            PhpClass::class,
            ['isAbstract', 'getName', 'getNamespace']
        );
        $finalClassMock      = $this->createMockExpectsOnlyMethodUsage(
            PhpClass::class,
            ['setAbstract', 'setNamespace', 'setParentClassName']
        );

        $classMock->expects($this->once())->method('isAbstract')->with()->willReturn(false);
        $classMock->expects($this->once())->method('getName')->with()->willReturn($nameMock);
        $classMock->expects($this->once())->method('getNamespace')->with()->willReturn($nameSpaceMock);

        $finalClassMock->expects($this->once())->method('setAbstract')->with(false);
        $finalClassMock->expects($this->once())->method('setParentClassName')->with()->willReturn($nameMock);
        $finalClassMock->expects($this->once())->method('setNamespace')->with()->willReturn($nameSpaceMock);

        $reflectionClassMock->expects($this->exactly(2))->method('isTrait')->with()->willReturn(false);
        $workingClass->expects($this->once())->method('getReflectionClass')->with()->willReturn($reflectionClassMock);

        $this->runNotPublicMethod($workingClass, $this->getTestingMethodName(), $classMock, $finalClassMock);
    }

    public function test_createFinalClass()
    {
        $this->wantToTestThisMethod();
        $workingClass = $this->getWorkingClass('getReflectionClass');

        $reflectionClassMock = $this->createMockExpectsOnlyMethodUsage(ReflectionClass::class, ['getShortName']);
        $shortNameMock       = $this->getString();
        $classNameMock       = $shortNameMock . ClassProxyProvider::CLASS_AFFIX;

        $phpClassMock          = $this->createMockExpectsNoUsage(PhpClass::class);
        $AbstractPhpStructMock = Test::double(AbstractPhpStruct::class, ['__construct' => $phpClassMock]);
        Test::double(
            PhpClass::class,
            [
                'initProperties' => true,
                'initConstants'  => true,
                'initInterfaces' => true,
            ]
        );

        $reflectionClassMock->expects($this->once())->method('getShortName')->with()->willReturn($shortNameMock);

        $workingClass->expects($this->once())->method('getReflectionClass')->with()->willReturn($reflectionClassMock);

        $expectingResult = new PhpClass($classNameMock);
        Test::cleanInvocations();
        $result = $this->runNotPublicMethod($workingClass, $this->getTestingMethodName());

        $this->assertEquals($expectingResult, $result);
        $AbstractPhpStructMock->verifyInvokedOnce('__construct', [$classNameMock]);//to make sure class name is correct
    }

    public function test_getCurrentClass()
    {
        $this->wantToTestThisMethod();
        $workingClass = $this->getWorkingClass('getReflectionClass');

        $fileNameMock    = $this->getString();
        $expectingResult = $classMock = $this->createMockExpectsNoUsage(PhpClass::class);

        $PhpClassMock = Test::double(PhpClass::class, ['fromFile' => $classMock]);

        $reflectionClassMock = $this->createMockExpectsOnlyMethodUsage(ReflectionClass::class, ['getFileName']);

        $reflectionClassMock->expects($this->once())->method('getFileName')->with()->willReturn($fileNameMock);

        $workingClass->expects($this->once())->method('getReflectionClass')->with()->willReturn($reflectionClassMock);

        $result = $this->runNotPublicMethod($workingClass, $this->getTestingMethodName());
        $this->assertEquals($expectingResult, $result);

        $PhpClassMock->verifyInvokedOnce('fromFile', [$fileNameMock]);
    }

    public function test_defineClass()
    {
        $this->wantToTestThisMethod();
        $workingClass = $this->getWorkingClass('getCodeGenerator');

        $newClassName = $this->getString();
        $evalPartMock = "namespace " . self::UNIT_TEST_NAME_SPACE . " {class {$newClassName} {}}";

        $finalClassMock = $this->createMockExpectsNoUsage(PhpClass::class);
        $generatorMock  = $this->createMockExpectsOnlyMethodUsage(CodeGenerator::class, ['generate']);

        $generatorMock->expects($this->once())->method('generate')->with($finalClassMock)->willReturn($evalPartMock);

        $workingClass->expects($this->once())->method('getCodeGenerator')->with()->willReturn($generatorMock);

        $this->runNotPublicMethod($workingClass, $this->getTestingMethodName(), $finalClassMock);
        $this->assertTrue(class_exists(self::UNIT_TEST_NAME_SPACE . '\\' . $newClassName));
    }

    public function test_addUseStatements()
    {
        $this->wantToTestThisMethod();
        $workingClass = $this->getWorkingClass();

        $classMock      = $this->createMockExpectsOnlyMethodUsage(PhpClass::class, ['getUseStatements']);
        $finalClassMock = $this->createMockExpectsOnlyMethodUsage(PhpClass::class, ['addUseStatement']);

        $useStatementMocks = $this->getArray();
        $useStatementWith  = [];

        foreach ($useStatementMocks as $statement) {
            $useStatementWith[] = [$statement];
        }

        $classMock->expects($this->once())->method('getUseStatements')->with()->willReturn($useStatementMocks);
        $finalClassMock->expects($this->exactly(count($useStatementMocks)))->method('addUseStatement')
            ->withConsecutive(... $useStatementWith);

        $this->runNotPublicMethod($workingClass, $this->getTestingMethodName(), $classMock, $finalClassMock);
    }

    public function test_addMethods()
    {
        $this->wantToTestThisMethod();
        $workingClass = $this->getWorkingClass('assignNewMethod');

        $classMock      = $this->createMockExpectsOnlyMethodUsage(PhpClass::class, ['getMethods']);
        $finalClassMock = $this->createMockExpectsNoUsage(PhpClass::class);

        $methodMocks      = $this->getArray(PhpMethod::class);
        $assignMethodWith = [];

        foreach ($methodMocks as $method) {
            $assignMethodWith[] = [$method, $finalClassMock];
        }

        $classMock->expects($this->once())->method('getMethods')->with()->willReturn($methodMocks);

        $workingClass->expects($this->exactly(count($methodMocks)))->method('assignNewMethod')
            ->withConsecutive(... $assignMethodWith);

        $this->runNotPublicMethod($workingClass, $this->getTestingMethodName(), $classMock, $finalClassMock);
    }

    /**
     * @return string
     */
    protected function getWorkingClassName(): string
    {
        return ClassProxyProvider::class;
    }
}
