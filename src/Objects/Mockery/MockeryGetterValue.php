<?php
/**
 * Created by PhpStorm.
 * User: mlocmelis
 * Date: 9/12/18
 * Time: 2:31 PM
 */

namespace Tests\Neznajka\Codeception\Engine\Objects\Mockery;

use Tests\Neznajka\Codeception\Engine\Contract\ExpectsSelfMethodCallInterface;
use Tests\Neznajka\Codeception\Engine\Contract\HaveConsecutiveMethodCallsInterface;


/**
 * Class MockeryGetterValue
 * @package Tests\Neznajka\Codeception\Engine\Objects
 */
class MockeryGetterValue implements ExpectsSelfMethodCallInterface, HaveConsecutiveMethodCallsInterface
{
    /** @var string */
    protected $getterMethodName;
    /** @var mixed */
    protected $expectedResponse;
    /** @var mixed */
    protected $arguments;

    /**
     * MockeryGetterValue constructor.
     * @param string $getterMethodName
     * @param mixed $expectedResponse
     */
    public function __construct(string $getterMethodName, $expectedResponse)
    {
        $this->getterMethodName = $getterMethodName;
        $this->expectedResponse = $expectedResponse;
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
    public function getExpectedResponse()
    {
        return $this->expectedResponse;
    }

    /**
     * @return array
     */
    protected function getArguments()
    {
        return $this->arguments;
    }
}
