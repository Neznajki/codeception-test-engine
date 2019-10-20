<?php
declare(strict_types=1);

namespace Tests\Neznajka\Codeception\Engine\Traits;

use Exception;
use Symfony\Component\HttpKernel\Kernel;
use Psr\Container\ContainerInterface;
use ReflectionClass;
use ReflectionException;
use RuntimeException;
use Symfony\Component\DependencyInjection\Container;
use Tests\Neznajka\Codeception\Engine\Service\ClassConstructor;

/**
 * Class ArgumentResolverTrait
 * @package Tests\Neznajka\Codeception\Engine\Traits
 */
trait SymfonyKernelTrait
{
    use ArgumentsPrepareTrait{
        prepareArgs as protected;
    }

    /** @var Container */
    protected $container;

    /**
     * to place items into contracts
     * @param string $placeName
     * @param $data
     * @throws ReflectionException
     */
    protected function placeClass(string $placeName, $data)
    {
        $this->getContainer()->set($placeName, $data);
    }

    /**
     * @param string $className
     * @return object
     *
     * @throws Exception
     */
    protected function createClass(string $className)
    {
        if (! $this->getContainer()->has($className)) {
            $classConstructor = new ClassConstructor($this->getContainer());

            $this->getContainer()->set($className, $classConstructor->createInstance($className));
        }

        return $this->getContainer()->get($className);
    }

    /**
     * @param string|object $class
     * @param string $methodName
     * @param array $customArgumentValues
     * @return array
     * @throws RuntimeException
     * @throws ReflectionException
     * @throws Exception
     */
    protected function getMethodArguments(
        $class,
        string $methodName,
        array $customArgumentValues = []
    ): array {
        $classReflection = new ReflectionClass($class);

        if (! $classReflection->hasMethod($methodName)) {
            throw new RuntimeException("{$methodName} not exist in class {$class}");
        }
        $method = $classReflection->getMethod($methodName);

        return array_values($this->prepareArgs($method, $customArgumentValues));
    }

    /**
     * @return ContainerInterface|Container
     * @throws ReflectionException
     */
    protected function getContainer(): ContainerInterface
    {
        if ($this->container === null) {
            $kernel = $this->getKernel();
            $this->container = $kernel->getContainer();
        }

        return $this->container;
    }

    /**
     * @return Kernel
     * @throws ReflectionException
     */
    protected function getKernel(): Kernel
    {
        if (! getenv('KERNEL_CLASS_NAME')) {
            throw new RuntimeException("please define full Kernel Class Name");
        }

        $className = getenv('KERNEL_CLASS_NAME');
        if (! class_exists($className)) {
            throw new RuntimeException('please implement Kernel class (required to get container)');
        }

        $classReflection = new ReflectionClass($className);
        /** @var Kernel $kernel */
        $kernel = $classReflection->newInstanceArgs(['test', true]);
        $kernel->boot();

        return $kernel;
    }
}
