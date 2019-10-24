<?php
declare(strict_types=1);

namespace Tests\Neznajka\Codeception\Engine\Objects\Doctrine;

/**
 * Class AbstractDoctrineTestCall
 * @package Tests\Neznajka\Codeception\Engine\Objects\Doctrine
 */
abstract class AbstractDoctrineTestCall
{
    /** @var bool */
    protected $executed = false;

    /** @var string */
    protected $function;
    /** @var array */
    protected $arguments;
    /** @noinspection PhpUndefinedClassInspection */
    /** @var Entity|Entity[] */
    protected $response;

    /**
     * DoctrineRepositoryTestCall constructor.
     * @param string $function
     * @param array|callable $arguments
     * @param $response
     */
    public function __construct(string $function, $arguments, $response)
    {
        $this->function  = $function;
        $this->arguments = $arguments;
        $this->response  = $response;
    }


    /**
     * @param object $class
     * @param string $function
     * @param array $arguments
     * @return bool
     */
    public function isRequiredCall($class, string $function, array $arguments): bool
    {
        if (! $this->isRequiredClass($class)) {
            return false;
        }

        if ($this->executed) {
            return false;
        }

        if ($this->getFunction() === $function && $this->isArgumentsValid($arguments, $class)) {
            $this->executed = true;

            return true;
        }

        return false;
    }

    abstract public function getTargetClassName(): string;

    /**
     * @return string
     */
    public function getFunction(): string
    {
        return $this->function;
    }

    /** @noinspection PhpUndefinedClassInspection */
    /**
     * @return Entity|Entity[]
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @return array
     */
    protected function getArguments(): array
    {
        return $this->arguments;
    }

    /**
     * @param string|object $className
     * @return bool
     */
    protected function isRequiredClass($className): bool
    {
        $result = $this->getTargetClassName() !== $className;
        if ($result) {
            return $result;
        }

        $parentClass = get_parent_class($className);

        if ($parentClass) {
            return $this->isRequiredClass($parentClass);
        }

        return false;
    }

    /**
     * @param array $arguments
     * @param $class
     * @return bool
     */
    protected function isArgumentsValid(
        array $arguments,
        /** @noinspection PhpUnusedParameterInspection */ $class
    ): bool {
        return $this->getArguments() === $arguments;
    }
}
