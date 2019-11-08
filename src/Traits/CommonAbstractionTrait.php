<?php /** @noinspection PhpUndefinedClassInspection */

/**
 * Created by PhpStorm.
 * User: mlocmelis
 * Date: 3/1/19
 * Time: 11:13 AM
 */

namespace Tests\Neznajka\Codeception\Engine\Traits;

use LogicException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\RuntimeException;
use ReflectionClass;
use ReflectionException;
use Tests\Neznajka\Codeception\Engine\Service\ClassProxyProvider;
use Tests\Neznajka\Codeception\Engine\Traits\CodeceptionClass\UnitTrait;
use Tests\Neznajka\Codeception\Engine\ValueObject\TestCaseClassData;
use Tests\Neznajka\Codeception\Engine\ValueObject\TestCaseMethodData;
use UnitTester;

/**
 * Class CommonTraitFunctions
 * @package Tests\Neznajka\Codeception\Engine\Traits
 */
trait CommonAbstractionTrait
{
    use UnitTrait;

    /** @var UnitTester */
    protected $tester;
    /** @var string */
    protected $testClassName;
    /** @var null|array */
    protected $keepExistingCodeFunctions = null;
    /** @var TestCaseClassData */
    protected $classData;

    /**
     * @return string
     * @throws ReflectionException
     */
    protected function getWorkingClassNameSpace(): string
    {
        return $this->getClassData()->getNameSpace();
    }

    /**
     * @return UnitTester
     */
    protected function getTester(): UnitTester
    {
        return $this->tester;
    }

    /**
     * @param mixed ...$mockedMethods
     * @return MockObject
     * @throws LogicException
     * @throws RuntimeException
     * @throws ReflectionException
     */
    protected function getWorkingClassPrivateMock(... $mockedMethods)
    {
        throw new RuntimeException('TODO fix private mocking could not suite latest nikic/php-parser');
//        $classReflection = $this->getClassProxyProvider($this->getClassData()->getClassReflection())->createTestClass();
//        $this->testClassName = $classReflection->getName();
//
//        return $this->getWorkingClass(... func_get_args());
    }

    /**
     * @param array $mockedMethods
     * @return MockObject
     * @throws LogicException
     * @throws RuntimeException
     * @throws ReflectionException
     */
    protected function getWorkingClass(... $mockedMethods)
    {//TODO rework to object with primary method define (should error on any not defined method)
        if ($this->testClassName === null) {
            $this->testClassName = $this->getWorkingClassName();
        }
        $classReflection = new ReflectionClass($this->testClassName);
        $allMethods      = $classReflection->getMethods();

        if ($this->getKeepExistingCodeFunctions() === null) {
            $this->setKeepExistingCodeFunctions([$this->getTestingMethodName()]);

        }

        $notExpectedMethods = [];
        $overriddenMethods = [];
        $methodFound = false;
        foreach ($allMethods as $method) {
            if (in_array($method->getName(), $this->getKeepExistingCodeFunctions())) {
                $methodFound = true;

                continue;
            }

            if (
                $method->isConstructor() ||
                $method->isFinal() ||
                $method->isDestructor() ||
                $method->isStatic() ||
                $method->isPrivate()
            ) {
                $this->tester->comment("WARNING: could not disable usage of method {$method}");

                continue;
            }
            if (! in_array($method->getName(), $mockedMethods)) {
                $notExpectedMethods[] = $method->getName();
            }

            $overriddenMethods[] = $method->getName();
        }

        if ($methodFound === false) {
            $testingMethod = print_r($this->getKeepExistingCodeFunctions(), true);
            throw new LogicException("could not find testing method {$testingMethod} please use syntax test_methodName or testMethodName");
        }

        $class = $classReflection->getName();
        if ($classReflection->isTrait()) {
            throw new RuntimeException('TODO fix private mocking could not suite latest nikic/php-parser');
//            $classReflection = $this->getClassProxyProvider($this->getClassData()->getClassReflection())->createTestTrait();
//            $class = $classReflection->getName();
        }

        $result = $this->getMockBuilder($class)
            ->setMethods($overriddenMethods)
            ->disableOriginalConstructor()
            ->enableProxyingToOriginalMethods()
            ->getMockForAbstractClass();

        foreach ($notExpectedMethods as $methodName) {
            $result->expects($this->never())->method($methodName);
        }

        $this->setKeepExistingCodeFunctions(null);
        $this->testClassName = null;

        return $result;
    }

    /**
     * @param string $methodName
     */
    protected function wantToTestMethod(string $methodName)
    {
        $this->tester->wantTo("make sure {$methodName} works");
    }

    /**
     * @throws LogicException
     */
    protected function wantToTestThisMethod()
    {
        $message = "make sure {$this->getTestingMethodName()} works";

        if ($this->getMethodData()->getFunctionCase()) {
            $message .= ", for case {$this->getMethodData()->getFunctionCase()}";
        }

        $this->tester->wantTo($message);
    }

    /**
     * @return string
     * @throws LogicException
     */
    protected function getTestingMethodName(): string
    {
        return $this->getMethodData()->getTargetFunction();
    }

    /**
     * @return array|null
     */
    protected function getKeepExistingCodeFunctions()
    {
        return $this->keepExistingCodeFunctions;
    }

    /**
     * @param array|null $keepExistingCodeFunctions
     *
     * @return $this
     */
    protected function setKeepExistingCodeFunctions(array $keepExistingCodeFunctions = null): self
    {
        $this->keepExistingCodeFunctions = $keepExistingCodeFunctions;

        return $this;
    }

    /**
     * @return TestCaseMethodData
     * @throws LogicException
     */
    protected function getMethodData(): TestCaseMethodData
    {
        foreach (debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS) as $item) {
            $function = $item['function'];
            if (preg_match('/^test/', $function)) {
                return new TestCaseMethodData($function);
            }
        }

        throw new LogicException("this method should be called from any test case method");
    }

    /**
     * @return TestCaseClassData
     * @throws ReflectionException
     */
    protected function getClassData(): TestCaseClassData
    {
        if ($this->classData === null) {
            $this->classData = new TestCaseClassData($this->getWorkingClassName());
        }

        return $this->classData;
    }

    /**
     * @return string
     */
    abstract protected function getWorkingClassName(): string;

    /**
     * @param ReflectionClass $classReflection
     * @return ClassProxyProvider
     * @codeCoverageIgnore
     */
    private function getClassProxyProvider(ReflectionClass $classReflection)
    {
        throw new RuntimeException('TODO fix private mocking could not suite latest nikic/php-parser');

//        return new ClassProxyProvider($classReflection);
    }
}
