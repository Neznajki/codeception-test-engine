<?php
declare(strict_types=1);

namespace Tests\Neznajka\Codeception\Engine\Objects;

use FunctionalTester;
use ReflectionClass;
use ReflectionException;
use ReflectionFunction;
use ReflectionParameter;
use RuntimeException;

/**
 * Class AfterAction
 * @package Tests\Neznajka\Codeception\Engine\Objects
 */
class CallableAction
{
    /** @var callable */
    protected $execution;

    /**
     * AfterAction constructor.
     * @param callable $execution
     */
    public function __construct(callable $execution)
    {
        $this->execution = $execution;
    }

    /**
     * @param FunctionalTester $I
     * @throws ReflectionException
     * @throws RuntimeException
     */
    public function handle(FunctionalTester $I)
    {
        $paramArr = $this->prepareArguments($I);
        $this->dispatchAction($paramArr);
    }

    /**
     * @return bool
     */
    public function resultTrue(): ?bool
    {
        return true;
    }

    /**
     * @param FunctionalTester $I
     * @return array
     * @throws RuntimeException
     * @throws ReflectionException
     */
    protected function prepareArguments(FunctionalTester $I): array
    {
        if (is_array($this->execution)) {
            return $this->prepareArgumentsForObjectCallable($I);
        }

        $reflection = new ReflectionFunction($this->execution);
        $params = $reflection->getParameters();

        return $this->getParamsArrayFromParameters($I, $params);
    }

    /**
     * @param FunctionalTester $I
     * @return array
     * @throws ReflectionException
     * @throws RuntimeException
     */
    protected function prepareArgumentsForObjectCallable(FunctionalTester $I): array
    {
        $classReflection = new ReflectionClass($this->execution[0]);

        $methodReflection = $classReflection->getMethod($this->execution[1]);
        $arguments = $methodReflection->getParameters();

        return $this->getParamsArrayFromParameters($I, $arguments);
    }

    /**
     * @param FunctionalTester $I
     * @param ReflectionParameter[] $arguments
     * @return array
     * @throws RuntimeException
     */
    protected function getParamsArrayFromParameters(FunctionalTester $I, array $arguments): array
    {
        $result = [];
        foreach ($arguments as $parameter) {
            $paramTypeName = $parameter->getType()->getName();
            switch ($paramTypeName) {
                case FunctionalTester::class:
                    $result[] = $I;
                    break;
                default:
                    throw new RuntimeException("unknown parameter type {$paramTypeName}");
            }
        }

        return $result;
    }

    /**
     * @param array $paramArr
     */
    protected function dispatchAction(array $paramArr)
    {
        call_user_func($this->execution, ... $paramArr);
    }
}
