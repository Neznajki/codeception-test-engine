<?php /** @noinspection PhpDocMissingThrowsInspection */

/** @noinspection PhpUnhandledExceptionInspection */


namespace Tests\Neznajka\Codeception\Engine\Service;


use Psr\Container\ContainerInterface;
use ReflectionClass;
use Tests\Neznajka\Codeception\Engine\Traits\ArgumentsPrepareTrait;

class ClassConstructor
{
    use ArgumentsPrepareTrait;

    /** @var ContainerInterface */
    protected $container;

    /**
     * ClassConstructor constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $className
     * @param array $arguments
     * @return object
     * @throws \ReflectionException
     */
    public function createInstance(string $className, $arguments = [])
    {
        $reflection = new ReflectionClass($className);

        if ($reflection->getConstructor()) {
            $arguments = $this->prepareArgs($reflection->getConstructor(), $arguments);
        }
        return  $reflection->newInstanceArgs($arguments);
    }

    /**
     * @return ContainerInterface
     */
    protected function getContainer(): ContainerInterface
    {
        return $this->container;
    }
}
