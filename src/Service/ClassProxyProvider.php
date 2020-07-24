<?php
/**
 * Created by PhpStorm.
 * User: mlocmelis
 * Date: 3/12/19
 * Time: 1:05 PM
 */

namespace Tests\Neznajka\Codeception\Engine\Service;

use LimitIterator;
use LogicException;
use Nette\InvalidArgumentException;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpNamespace;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use RuntimeException;
use SplFileObject;

/**
 * Class ClassProxyProvider
 * @package Tests\Neznajka\Codeception\Engine\Service
 *
 * classes with private methods will show no coverage
 */
class ClassProxyProvider
{
    const CLASS_AFFIX = 'ForTestCase';
    const TRAIT_AFFIX = 'ForTraitTestCase';
    const REPLACING_VISIBILITY = 'private';
    const NEW_VISIBILITY = 'protected';

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
     * @throws LogicException
     * @throws ReflectionException
     * @throws RuntimeException
     * @throws InvalidArgumentException
     */
    public function createTestClass(): ReflectionClass
    {
        $classReflection = $this->getReflectionClass();
        $testClassName = $classReflection->getName() . self::CLASS_AFFIX;

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
     * @throws InvalidArgumentException
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
     * @throws LogicException
     * @throws RuntimeException
     * @throws InvalidArgumentException
     */
    protected function createClassWithProtectedMethods()
    {
        $class = $this->getCurrentClass();
        $finalClass = $this->createFinalClass();

        $this->prepareClassAttributes($class, $finalClass);
        $this->addUseStatements($class, $finalClass);
        $this->addMethods($class, $finalClass);
        $this->addProperties($class, $finalClass);

        $this->defineClass($finalClass);
    }

    /**
     * @param string $testClassName
     * @throws InvalidArgumentException
     */
    protected function createTestTraitClass(string $testClassName)
    {
        $classReflection = $this->getReflectionClass();

        $class = $this->createNewClass($testClassName);

        $class->setAbstract(true);
        $class->addTrait($classReflection->getShortName());

        $this->defineClass($class);
    }

    /**
     * @param ClassType $class
     * @param ClassType $finalClass
     * @throws InvalidArgumentException
     */
    protected function addProperties(ClassType $class, ClassType $finalClass)
    {
        $classReflection = $this->getReflectionClass();
        if ($classReflection->isTrait()) {
            return;
        }

        $properties = $class->getProperties();
        foreach ($properties as $property) {
            if ($property->getVisibility() === self::REPLACING_VISIBILITY) {
                $property->setVisibility(self::NEW_VISIBILITY);
            }
        }

        $finalClass->setProperties($properties);
    }

    /**
     * @param ClassType $class
     * @param ClassType $finalClass
     * @throws InvalidArgumentException
     */
    protected function prepareClassAttributes(ClassType $class, ClassType $finalClass)
    {
        $classReflection = $this->getReflectionClass();
        $finalClass->setAbstract($class->isAbstract() || $classReflection->isTrait());

        if ($classReflection->isTrait()) {
            $finalClass->addTrait($class->getName());
        } else {
            $finalClass->setExtends($classReflection->getName());
        }
    }

    /**
     * @return ClassType
     * @throws InvalidArgumentException
     */
    protected function createFinalClass(): ClassType
    {
        $reflectionClass = $this->getReflectionClass();
        return new ClassType(
            $reflectionClass->getShortName() . self::CLASS_AFFIX,
            new PhpNamespace($reflectionClass->getNamespaceName())
        );
    }

    /**
     * @return ClassType
     * @throws LogicException
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    protected function getCurrentClass(): ClassType
    {
        $reflectionClass = $this->getReflectionClass();
        $result = ClassType::from(
            $reflectionClass->getName()
        );

        foreach ($reflectionClass->getMethods() as $method) {
            $methodName = $method->getName();
            if (! $result->hasMethod($methodName)) {
                continue;
            }

            $result->getMethod($methodName)->setBody(
                $this->extractBody($method)
            );
        }

        return $result;
    }

    /**
     * @param ReflectionMethod $method
     * @return string
     * @throws LogicException
     * @throws RuntimeException
     */
    protected function extractBody(ReflectionMethod $method): string
    {
        $f = $method->getFileName();
        $start_line = $method->getStartLine() - 1;
        $end_line = $method->getEndLine();

        $file = new SplFileObject($f);
        $fileIterator = new LimitIterator($file, $start_line, $end_line - $start_line);
        $body = '';

        foreach ($fileIterator as $line) {
            $body .= $line;
        }

        return preg_replace(
            '/^[^{]*{/',
            '',
            preg_replace('/}[^}]*$/', '', $body)
        );
    }

    /**
     * @return string
     */
    protected function getClassHeaders(): string
    {
        $contents = file_get_contents($this->getReflectionClass()->getFileName());
        $contents = preg_replace('/<\\?(php)?\s+/', '', $contents);

        return preg_replace('/\n\s*(class|abstract class|trait)\s.+$/is', '', $contents);
    }

    /**
     * @param ClassType $finalClass
     */
    protected function defineClass(ClassType $finalClass)
    {
        $classDeclaration =
            $this->getClassHeaders() . PHP_EOL . (string)$finalClass;

        $fixedReturnTypeSelf =
            preg_replace('/(:[^{]*)self([^{]*{)/', sprintf('$1%s$2', $this->getReflectionClass()->getShortName()), $classDeclaration);

        eval($fixedReturnTypeSelf);
    }

    /**
     * @param ClassType $class
     * @param ClassType $finalClass
     */
    protected function addUseStatements(ClassType $class, ClassType $finalClass)
    {
        foreach ($class->getTraits() as $useStatement) {
            $finalClass->addTrait($useStatement);
        }
    }

    /**
     * @param ClassType $class
     * @param ClassType $finalClass
     * @throws InvalidArgumentException
     */
    protected function addMethods(ClassType $class, ClassType $finalClass)
    {
        $methods = $class->getMethods();

        foreach ($methods as $method) {
            if ($method->getVisibility() === self::REPLACING_VISIBILITY) {
                $method->setVisibility(self::NEW_VISIBILITY);
            }
        }

        $finalClass->setMethods($methods);
    }

    /**
     * @param string          $testClassName
     * @return ClassType
     * @throws InvalidArgumentException
     */
    protected function createNewClass(string $testClassName): ClassType
    {
        $namespace = new PhpNamespace($this->getReflectionClass()->getNamespaceName());

        return new ClassType($testClassName, $namespace);
    }
}
