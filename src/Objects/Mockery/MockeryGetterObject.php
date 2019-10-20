<?php
/**
 * Created by PhpStorm.
 * User: mlocmelis
 * Date: 9/12/18
 * Time: 12:12 PM
 */

namespace Tests\Neznajka\Codeception\Engine\Objects\Mockery;

use Tests\Neznajka\Codeception\Engine\Contract\ExpectsSelfMethodCallInterface;


/**
 * Class MockeryObject
 * @package Tests\Neznajka\Codeception\Engine\Objects
 */
class MockeryGetterObject implements ExpectsSelfMethodCallInterface
{
    /** @var string */
    protected $getterMethodName;
    /** @var string */
    protected $resultClassName;
    /** @var int */
    protected $callTimes;

    /**
     * MockeryGetterObject constructor.
     * @param string $getterMethodName
     * @param $resultClassName
     * @param int $callTimes
     */
    public function __construct(string $getterMethodName, string $resultClassName, int $callTimes)
    {
        $this->getterMethodName = $getterMethodName;
        $this->resultClassName  = $resultClassName;
        $this->callTimes        = $callTimes;
    }

    /**
     * @return string
     */
    public function getGetterMethodName(): string
    {
        return $this->getterMethodName;
    }

    /**
     * @return mixed
     */
    public function getResultClassName(): string
    {
        return $this->resultClassName;
    }

    /**
     * @return int
     */
    public function getCallTimes(): int
    {
        return $this->callTimes;
    }
}
