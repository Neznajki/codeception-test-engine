<?php /** @noinspection PhpUnhandledExceptionInspection */


namespace Tests\Neznajka\Codeception\Engine\Traits;

use Exception;
use InvalidArgumentException;
use Psr\Container\ContainerInterface;
use ReflectionException;
use ReflectionMethod;
use ReflectionParameter;
use Symfony\Component\DependencyInjection\Container;
use Tests\Neznajka\Codeception\Engine\Service\ClassConstructor;

/**
 * Trait ArgumentsPrepareTrait
 * @package Tests\Neznajka\Codeception\Engine\Traits
 */
trait ArgumentsPrepareTrait
{

    /** @var ReflectionMethod */
    private $reflection;
    /** @var array */
    private $knownParams;

    /**
     * @param ReflectionMethod $reflection
     * @param array $params
     * @return array
     * @throws Exception
     */
    public function prepareArgs(ReflectionMethod $reflection, array $params): array
    {
        $this->setReflection($reflection)->setKnownParams($params);

        $args = [];
        /** @var ReflectionParameter $parameter */
        foreach ($reflection->getParameters() as $parameter) {
            $args[$parameter->getName()] = $this->resolveArgument($parameter);
        }

        return $args;
    }

    /**
     * @return ContainerInterface|Container
     */
    abstract protected function getContainer(): ContainerInterface;

    /**
     * @param ReflectionParameter $parameter
     * @return mixed
     * @throws ReflectionException
     */
    protected function resolveArgument(ReflectionParameter $parameter)
    {
        if ($parameter->hasType() && ! $parameter->getType()->isBuiltin()) {
            $className = (string)$parameter->getType();
            if (! $this->getContainer()->has($className)) {
                $instanceCreator = new ClassConstructor($this->getContainer());

                $this->getContainer()->set($className, $instanceCreator->createInstance($className, $this->getKnownParams()));
            }

            return $this->getContainer()->get($className);
        }

        return $this->resolveCustomArgument($parameter);
    }

    /**
     * @param ReflectionParameter $parameter
     * @return mixed
     * @throws ReflectionException
     */
    protected function resolveCustomArgument(ReflectionParameter $parameter)
    {
        $knownParams = $this->getKnownParams();
        if (! $parameter->isDefaultValueAvailable() && ! isset($knownParams[$parameter->getName()])) {
            throw new InvalidArgumentException(
                "({$parameter->getType()}) type Parameter \"{$parameter->getName()}\" is mandatory for {$this->getFullMethodName()}"
            );
        }

        if (isset($knownParams[$parameter->getName()])) {
            return $this->getFromKnownParameters($parameter);
        }

        return $parameter->getDefaultValue();
    }

    /**
     * @param ReflectionParameter $parameter
     * @param mixed $value
     * @return mixed
     */
    protected function convertAndValidateKnownValue(ReflectionParameter $parameter, $value)
    {
        $types = $this->getTypes();

        switch ($types[$parameter->getName()]) {
            case 'string':
                if (! is_string($value)) {
                    throw new InvalidArgumentException(
                        'Parameter "' . $parameter->getName() . '" must be string for ' . $this->getFullMethodName()
                    );
                }
                break;
            case 'bool':
                // cast to bool, if needed
                if ($value === 1 || $value === 0 || $value === "1" || $value === "0") {
                    $value = (bool)($value);
                }

                if (! is_bool($value)) {
                    throw new InvalidArgumentException(
                        'Parameter "' . $parameter->getName() . '" must be bool for ' . $this->getFullMethodName()
                    );
                }
                break;
            case 'int':
                if (! is_numeric($value)) {
                    throw new InvalidArgumentException(
                        'Parameter "' . $parameter->getName() . '" must be numeric for ' . $this->getFullMethodName()
                    );
                }
                break;
            case 'array':
                if (! is_array($value)) {
                    throw new InvalidArgumentException(
                        'Parameter "' . $parameter->getName() . '" must be array for ' . $this->getFullMethodName()
                    );
                }
                break;
        }

        return $value;
    }

    /**
     * @return array
     */
    protected function getTypes(): array
    {
        static $types = [];

        if (empty($types)) {
            foreach ($this->getReflection()->getParameters() as $parameter) {
                if ($parameter->hasType()) {
                    $types[$parameter->getName()] = strval($parameter->getType());
                }
            }
        }

        return $types;
    }

    /**
     * @param ReflectionParameter $parameter
     * @return bool|mixed
     * @throws InvalidArgumentException
     */
    protected function getFromKnownParameters(ReflectionParameter $parameter)
    {
        $knownParams   = $this->getKnownParams();
        $parameterName = $parameter->getName();
        $value         = $knownParams[$parameterName];

        if (array_key_exists($parameterName, $this->getTypes())) {

            $value = $this->convertAndValidateKnownValue($parameter, $value);
        }

        return $value;
    }

    protected function getFullMethodName()
    {
        $reflectionMethod = $this->getReflection();

        return $reflectionMethod->getDeclaringClass()->getName() . '::' . $reflectionMethod->getName();
    }

    /**
     * @return ReflectionMethod
     */
    protected function getReflection(): ReflectionMethod
    {
        return $this->reflection;
    }

    /**
     * @param ReflectionMethod $reflection
     *
     * @return $this
     */
    protected function setReflection(ReflectionMethod $reflection): self
    {
        $this->reflection = $reflection;

        return $this;
    }

    /**
     * @return array
     */
    protected function getKnownParams(): array
    {
        return $this->knownParams;
    }

    /**
     * @param array $knownParams
     *
     * @return $this
     */
    protected function setKnownParams(array $knownParams): self
    {
        $this->knownParams = $knownParams;

        return $this;
    }
}
