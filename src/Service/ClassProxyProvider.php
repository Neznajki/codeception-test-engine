<?php
/**
 * Created by PhpStorm.
 * User: mlocmelis
 * Date: 3/12/19
 * Time: 1:05 PM
 */

namespace Tests\Neznajka\Codeception\Engine\Service;

use gossi\codegen\generator\CodeGenerator;
use gossi\codegen\model\PhpClass;
use gossi\codegen\model\PhpMethod;
use gossi\codegen\model\PhpProperty;
use InvalidArgumentException;
use LogicException;
use ReflectionClass;
use ReflectionException;
use ReflectionType;

/**
 * Class ClassProxyProvider
 * @package Tests\Neznajka\Codeception\Engine\Service
 *
 * classes with private methods will show no coverage
 */
class ClassProxyProvider
{
    const CLASS_AFFIX          = '_ForTestCase';
    const TRAIT_AFFIX          = '_ForTraitTestCase';
    const REPLACING_VISIBILITY = 'private';
    const NEW_VISIBILITY       = 'protected';

    /** @var CodeGenerator */
    private $codeGenerator;
    /** @var ReflectionClass */
    private $reflectionClass;

    /**
     * ClassProxyProvider constructor.
     * @param ReflectionClass $reflectionClass
     */
    public function __construct(ReflectionClass $reflectionClass)
    {
        $this->reflectionClass = $reflectionClass;
    }

    /**
     * @return ReflectionClass
     * @throws InvalidArgumentException
     * @throws LogicException
     * @throws ReflectionException
     */
    public function createTestClass(): ReflectionClass
    {
        $classReflection = $this->getReflectionClass();
        $testClassName   = $classReflection->getName() . self::CLASS_AFFIX;

        if (! class_exists($testClassName)) {
            $this->createClassWithProtectedMethods();
        }

        if (! class_exists($testClassName)) {
            throw new LogicException("{$testClassName} does not exists");
        }

        return new ReflectionClass($testClassName);
    }

    /**
     * @return ReflectionClass
     * @throws ReflectionException
     */
    public function createTestTrait(): ReflectionClass
    {
        $classReflection = $this->getReflectionClass();

        $testClassName = $classReflection->getName() . self::TRAIT_AFFIX;

        if (! class_exists($testClassName)) {
            $this->createTestTraitClass($testClassName);
        }

        return new ReflectionClass($testClassName);
    }


    /**
     * @return ReflectionClass
     */
    protected function getReflectionClass(): ReflectionClass
    {
        return $this->reflectionClass;
    }

    /**
     * @return CodeGenerator
     */
    protected function getCodeGenerator(): CodeGenerator
    {
        if (empty($this->codeGenerator)) {
            $this->codeGenerator = new CodeGenerator(
                [
                    'generateDocblock'        => false,
                    'generateScalarTypeHints' => true,
                    'generateReturnTypeHints' => true,
                ]
            );
        }

        return $this->codeGenerator;
    }

    /**
     * @param PhpMethod $method
     * @param PhpClass $finalClass
     * @throws InvalidArgumentException
     * @throws ReflectionException
     */
    protected function assignNewMethod(PhpMethod $method, PhpClass $finalClass)
    {
        if ($method->getVisibility() === self::REPLACING_VISIBILITY) {
            $method->setVisibility(self::NEW_VISIBILITY);
        }

        $this->fixReturnType($method);
        $finalClass->setMethod($method);
    }

    /**
     * @throws InvalidArgumentException
     * @throws ReflectionException
     */
    protected function createClassWithProtectedMethods()
    {
        $class      = $this->getCurrentClass();
        $finalClass = $this->createFinalClass();

        $this->prepareClassAttributes($class, $finalClass );
        $this->addUseStatements($class, $finalClass);
        $this->addMethods($class, $finalClass);
        $this->addProperties($class, $finalClass);

        $this->defineClass($finalClass);
    }

    /**
     * @param string $testClassName
     */
    protected function createTestTraitClass(string $testClassName)
    {
        $classReflection = $this->getReflectionClass();
        $class           = PhpClass::create($testClassName);

        $class->setAbstract(true);
        $class->setNamespace($classReflection->getNamespaceName());
        $class->addTrait($classReflection->getShortName());

        $this->defineClass($class);
    }

    /**
     * @param PhpMethod $method
     * @throws ReflectionException
     */
    protected function fixReturnType(PhpMethod $method)
    {
        $classReflection  = $this->getReflectionClass();

        $reflectionType = $classReflection->getMethod($method->getName())->getReturnType();
        if ($reflectionType instanceof ReflectionType) {
            $reflectionType = (string)$reflectionType;
        }

        if ($reflectionType === 'self') {
            $reflectionType = '\\' . $classReflection->getName();
        }

        if (
            (class_exists($reflectionType) || interface_exists($reflectionType)) &&
            preg_match('/^\\\\/', $reflectionType) === 0
        ) {
            $reflectionType = '\\' . $reflectionType;
        }

        $method->setType($reflectionType);
    }

    /**
     * @param PhpClass $class
     * @param PhpClass $finalClass
     * @throws InvalidArgumentException
     */
    protected function addProperties(PhpClass $class, PhpClass $finalClass)
    {
        $classReflection = $this->getReflectionClass();
        /** @var PhpProperty $property */
        foreach ($class->getProperties() as $property) {
            if ($classReflection->isTrait()) {
                continue;
            }
            if ($property->getVisibility() === self::REPLACING_VISIBILITY) {
                $property->setVisibility(self::NEW_VISIBILITY);
            }

            $finalClass->setProperty($property);
        }
    }

    /**
     * @param PhpClass $class
     * @param PhpClass $finalClass
     */
    protected function prepareClassAttributes(PhpClass $class, PhpClass $finalClass)
    {
        $classReflection = $this->getReflectionClass();
        $finalClass->setAbstract($class->isAbstract() || $classReflection->isTrait());

        if ($classReflection->isTrait()) {
            $finalClass->addTrait($class->getName());
        } else {
            $finalClass->setParentClassName($class->getName());
        }

        $finalClass->setNamespace($class->getNamespace());
    }

    /**
     * @return PhpClass
     */
    protected function createFinalClass(): PhpClass
    {
        return new PhpClass($this->getReflectionClass()->getShortName() . self::CLASS_AFFIX);
    }

    /**
     * @return PhpClass
     */
    protected function getCurrentClass(): PhpClass
    {
        return PhpClass::fromFile($this->getReflectionClass()->getFileName());
    }

    /**
     * @param PhpClass $finalClass
     */
    protected function defineClass(PhpClass $finalClass)
    {
        $classContents = $this->getCodeGenerator()->generate($finalClass);
        eval($classContents);
    }

    /**
     * @param PhpClass $class
     * @param PhpClass $finalClass
     */
    protected function addUseStatements(PhpClass $class, PhpClass $finalClass)
    {
        foreach ($class->getUseStatements() as $useStatement) {
            $finalClass->addUseStatement($useStatement);
        }
    }

    /**
     * @param PhpClass $class
     * @param PhpClass $finalClass
     * @throws InvalidArgumentException
     * @throws ReflectionException
     */
    protected function addMethods(PhpClass $class, PhpClass $finalClass)
    {
        /** @var PhpMethod $method */
        foreach ($class->getMethods() as $method) {
            $this->assignNewMethod($method, $finalClass);
        }
    }
}
