<?php
/**
 * Created by PhpStorm.
 * User: mlocmelis
 * Date: 3/18/19
 * Time: 10:47 AM
 */

namespace Tests\Neznajka\Unit\ValueObject;

/**
 * Class TestCaseMethodData
 * @package Tests\Neznajka\Unit\ValueObject
 */
class TestCaseMethodData
{
    const DELIMITER = "_case_";

    /** @var string */
    private $fullFunctionName;
    /** @var string */
    private $targetFunction;
    /** @var string|null */
    private $functionCase;

    /**
     * TestCaseData constructor.
     * @param string $fullFunctionName
     * @throws \LogicException
     */
    public function __construct(string $fullFunctionName)
    {
        $this->fullFunctionName = $fullFunctionName;

        $this->parseFunction($fullFunctionName);
    }

    /**
     * @return string
     */
    public function getFullFunctionName(): string
    {
        return $this->fullFunctionName;
    }

    /**
     * @return string
     */
    public function getTargetFunction(): string
    {
        return $this->targetFunction;
    }

    /**
     * @return string|null
     */
    public function getFunctionCase()
    {
        return $this->functionCase;
    }

    /**
     * @param string $fullFunctionName
     * @throws \LogicException
     */
    private function parseFunction(string $fullFunctionName)
    {
        @list($functionName, $case) = explode(self::DELIMITER, $fullFunctionName, 2);

        if ($case) {
            $this->functionCase = str_replace('_', ' ', $case);
        }

        $targetFunctionName = preg_replace('/test_?(.+)$/', '$1', $functionName);

        if ($targetFunctionName === $functionName) {
            throw new \LogicException("test function must start with test_ or test[A-Z_]");
        }

        $this->targetFunction = lcfirst($targetFunctionName);
    }
}
