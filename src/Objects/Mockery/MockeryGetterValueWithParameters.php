<?php
/**
 * Created by PhpStorm.
 * User: mlocmelis
 * Date: 9/12/18
 * Time: 3:37 PM
 */

namespace Tests\Neznajka\Unit\Objects\Mockery;

use Tests\Neznajka\Unit\Contract\HaveParametersInterface;


/**
 * Class MockeryGetterValueWithParameters
 * @package Tests\Neznajka\Unit\Objects\Mockery
 */
class MockeryGetterValueWithParameters extends MockeryGetterValue implements HaveParametersInterface
{
    /** @var array */
    protected $arguments;
    /** @var array */
    protected $preparedArguments;

    /**
     * MockeryGetterValue constructor.
     * @param string $getterMethodName
     * @param mixed $expectedResponse
     * @param array $arguments
     */
    public function __construct(string $getterMethodName, $expectedResponse, ... $arguments)
    {
        $this->arguments = $arguments;

        parent::__construct($getterMethodName, $expectedResponse);
    }

    /**
     * @return array
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }

    /**
     * @param array $arguments
     */
    public function setPreparedArguments(array $arguments)
    {
        $this->preparedArguments = $arguments;
    }

    /**
     * @return array
     */
    public function getPreparedArguments(): array
    {
        return $this->preparedArguments;
    }
}
