<?php
/**
 * Created by PhpStorm.
 * User: mlocmelis
 * Date: 3/1/19
 * Time: 10:54 AM
 */

namespace Tests\Neznajka\Codeception\Engine\Traits;

use DateTime;
use Exception;
use ReflectionClass;
use ReflectionException;

/**
 * Class RandomGenerationTrait
 * @package Tests\Neznajka\Codeception\Engine\Traits
 * @method string getWorkingClassNameSpace()
 */
trait RandomGenerationTrait
{
    /**
     * @param string $className
     * @return ReflectionClass
     * @throws ReflectionException
     */
    protected function getClass(string $className): ReflectionClass
    {
        if (! class_exists($className)) {
            $classCreationString = "namespace {$this->getWorkingClassNameSpace()}; class {$className} {}";
            eval($classCreationString);
        }

        return new ReflectionClass($this->getWorkingClassNameSpace() . '\\' . $className);
    }

    /**
     * @return string
     */
    protected function getString(): string
    {
        return uniqid("unitTest");
    }

    /**
     * @return int
     */
    protected function getInt(): int
    {
        static $result;

        if ($result === null) {
            $result = rand(1, 666);
        } else {
            $result++;
        }

        return $result;
    }

    /**
     * @return float
     */
    protected function getFloat(): float
    {
        static $result;

        if ($result === null) {
            $result = microtime(true);
        } else {
            $result++;
        }

        return $result;
    }

    /**
     * @param string $className
     * @return array
     */
    protected function getArray(string $className = null): array
    {
        $result = [];

        $items = rand(1, 5);
        for ($i = 0; $i < $items; $i++) {
            if ($className) {
                $result[] = $this->createMockExpectsNoUsage($className);
            } else {
                $result[] = $this->getString();
            }
        }

        return $result;
    }

    /**
     * @return DateTime
     * @throws Exception
     */
    protected function getDateTime(): DateTime
    {
        return new DateTime('@' . $this->getInt());
    }
}
