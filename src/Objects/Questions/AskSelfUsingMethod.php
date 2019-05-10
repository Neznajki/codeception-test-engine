<?php
/**
 * Created by PhpStorm.
 * User: mlocmelis
 * Date: 9/12/18
 * Time: 2:57 PM
 */

namespace Tests\Neznajka\Unit\Objects\Questions;

use Exception;
use Tests\Neznajka\Unit\Contract\ExpectsSelfMethodCallInterface;
use Tests\Neznajka\Unit\Contract\HaveConsecutiveMethodCallsInterface;
use Tests\Neznajka\Unit\Contract\QuestionInterface;


/**
 * Class AskSelfQuestion
 * @package Tests\Neznajka\Unit\Objects\Questions
 */
class AskSelfUsingMethod implements QuestionInterface, ExpectsSelfMethodCallInterface, HaveConsecutiveMethodCallsInterface
{
    /** @var string */
    protected $getterMethodName;
    /** @var mixed */
    protected $expectedValue;
    /** @var bool */
    protected $itemRequired;
    /** @var int */
    protected $callTimes;

    /**
     * AskSelfQuestion constructor.
     * @param string $methodName
     * @param mixed $expectedValue
     * @param int $callTimes
     */
    public function __construct(string $methodName, $expectedValue, int $callTimes)
    {
        $this->getterMethodName = $methodName;
        $this->expectedValue    = $expectedValue;
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
    public function getExpectedValue()
    {
        return $this->expectedValue;
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function getReversedExpectedValue()
    {
        if (is_bool($this->expectedValue)) {
            $result = ! $this->expectedValue;
        } elseif (is_object($this->expectedValue)) {
            $result = null;
        } elseif (is_float($this->expectedValue) || is_int($this->expectedValue)) {
            if (empty($this->expectedValue)) {
                $result = 1;
            } else {
                $result = 0;
            }
        } elseif (is_array($this->expectedValue)) {
            if (empty($this->expectedValue)) {
                $result = ['something'];
            } else {
                $result = [];
            }
        } else {
            throw new Exception("expected value is not supported please update logic");
        }

        return $result;
    }

    /**
     * @param bool $itemRequired
     */
    public function setItemRequired(bool $itemRequired)
    {
        $this->itemRequired = $itemRequired;
    }

    /**
     * @return bool
     */
    public function isItemRequired(): bool
    {
        return $this->itemRequired;
    }

    /**
     * @return int
     */
    public function getCallTimes(): int
    {
        return $this->callTimes;
    }
}
