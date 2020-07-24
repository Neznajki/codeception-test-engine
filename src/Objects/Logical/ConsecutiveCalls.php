<?php
/**
 * Created by PhpStorm.
 * User: mlocmelis
 * Date: 9/13/18
 * Time: 11:13 AM
 */

namespace Tests\Neznajka\Codeception\Engine\Objects\Logical;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\RuntimeException;
use PHPUnit\Framework\TestCase;
use Tests\Neznajka\Codeception\Engine\Contract\HaveConsecutiveMethodCallsInterface;
use Tests\Neznajka\Codeception\Engine\Contract\HaveParametersInterface;


/**
 * Class ConsecutiveCalls
 * @package Tests\Neznajka\Codeception\Engine\Objects\Logical
 */
class ConsecutiveCalls extends TestCase
{
    const MOCK_INDEX = '_mock';

    /** @var array */
    protected $methodArgumentsList = [];
    /** @var array */
    protected $methodResultList = [];
    /** @var MockObject */
    protected $mock;

    /**
     * @param HaveConsecutiveMethodCallsInterface $methodCallData
     * @param mixed $returnResult
     */
    public function addSingleConsecutiveMethodCall(
        HaveConsecutiveMethodCallsInterface $methodCallData,
        $returnResult
    ) {
        $getterMethodName = $methodCallData->getGetterMethodName();

        if ($methodCallData instanceof HaveParametersInterface) {
            $this->methodArgumentsList[$getterMethodName][] = $methodCallData->getPreparedArguments();
        } else {
            $this->methodArgumentsList[$getterMethodName][] = [];
        }

        $this->methodResultList[$getterMethodName][] = $returnResult;
    }

    /**
     * @param MockObject $class
     * @throws RuntimeException
     */
    public function assignConsecutiveMethodCalls($class)
    {
        foreach ($this->methodResultList as $methodName => $values) {
            $class->expects($this->exactly(count($values)))
                ->method($methodName)
                ->withConsecutive(... $this->methodArgumentsList[$methodName])
                ->willReturnOnConsecutiveCalls(... $values);
        }
    }
}
