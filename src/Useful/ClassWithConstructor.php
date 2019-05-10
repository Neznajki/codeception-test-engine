<?php
/**
 * Created by PhpStorm.
 * User: mlocmelis
 * Date: 3/6/19
 * Time: 11:22 AM
 */

namespace Tests\Neznajka\Unit\Useful;

use PHPUnit\Framework\MockObject\MockObject;

/**
 * Class ClassWithConstructor
 * @package Tests\Neznajka\Unit\Useful
 */
class ClassWithConstructor
{
    /** @var MockObject|ClassWithConstructor */
    public static $mockObject;

    /**
     * ClassWithConstructor constructor.
     * @param mixed ...$anythingHere
     * @throws \LogicException
     */
    public function __construct(... $anythingHere)
    {
        foreach (func_get_args() as $pos => $arg) {
            $this->testIncomingParameter($pos, $arg);
        }

        static::$mockObject = null;
    }

    /**
     * @param ClassWithConstructor $mockObject
     */
    public function setMockObject(ClassWithConstructor $mockObject = null)
    {
        static::$mockObject = $mockObject;
    }

    /**
     * @param $pos
     * @param $value
     * @throws \LogicException
     */
    protected function testIncomingParameter($pos, $value)
    {
        if (static::$mockObject === null) {
            throw new \LogicException('please create mock that will test everything $this->getDynamicConstructorTestClass(... $arguments)');
        }

        static::$mockObject->testIncomingParameter($pos, $value);
    }
}
