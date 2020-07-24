<?php
/**
 * Created by PhpStorm.
 * User: mlocmelis
 * Date: 9/12/18
 * Time: 3:37 PM
 */

namespace Tests\Neznajka\Codeception\Engine\Objects\Mockery;

use Tests\Neznajka\Codeception\Engine\Contract\HaveParametersInterface;


/**
 * Class MockeryGetterValueWithParameters
 * @package Tests\Neznajka\Codeception\Engine\Objects\Mockery
 */
class MockeryGetterValueWithParametersQuestionable extends MockeryGetterValue implements HaveParametersInterface
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
    public function setArguments(array $arguments)
    {
        $this->arguments = $arguments;
    }

    /**
     * @return array
     */
    public function getPreparedArguments(): array
    {
        return $this->preparedArguments;
    }

    /**
     * @param mixed $preparedArguments
     */
    public function setPreparedArguments(array $preparedArguments)
    {
        $this->preparedArguments = $preparedArguments;
    }
}
