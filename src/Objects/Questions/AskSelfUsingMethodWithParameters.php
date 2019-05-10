<?php
/**
 * Created by PhpStorm.
 * User: mlocmelis
 * Date: 9/12/18
 * Time: 2:57 PM
 */

namespace Tests\Neznajka\Unit\Objects\Questions;

use Tests\Neznajka\Unit\Contract\HaveParametersInterface;


/**
 * Class AskSelfQuestion
 * @package Tests\Neznajka\Unit\Objects\Questions
 */
class AskSelfUsingMethodWithParameters extends AskSelfUsingMethod implements HaveParametersInterface
{
    /** @var array */
    protected $arguments;
    /** @var array */
    protected $preparedArguments;

    /**
     * AskSelfQuestion constructor.
     * @param string $methodName
     * @param bool $expectedValue
     * @param int $callTimes
     * @param array $arguments
     */
    public function __construct(string $methodName, $expectedValue, int $callTimes, array $arguments = [])
    {
        $this->arguments        = $arguments;
        parent::__construct($methodName, $expectedValue, $callTimes);
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
