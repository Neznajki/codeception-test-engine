<?php
declare(strict_types=1);

namespace Tests\Neznajka\Codeception\Engine\Objects\ResponseTester;

use FunctionalTester;
use InvalidArgumentException;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Exception;

/**
 * Class ArrayResponseChecker
 * @package Tests\Neznajka\Codeception\Engine\Objects
 */
class TestResponseRecursiveChecker
{

    /** @var Assert|FunctionalTester */
    protected $functionalTester;
    /** @var mixed */
    protected $expectedResponse;
    /** @var bool */
    protected $shouldBeSame;

    /**
     * FunctionalTestArrayResponseChecker constructor.
     * @param Assert|FunctionalTester $functionalTester
     * @param mixed $expectedResponse
     * @param bool $shouldBeSame same means objects have same link or should have same setup
     */
    public function __construct(
        $functionalTester,
        $expectedResponse,
        bool $shouldBeSame = false
    ) {
        $this->functionalTester = $functionalTester;
        $this->expectedResponse = $expectedResponse;
        $this->shouldBeSame     = $shouldBeSame;
    }

    /**
     * @param mixed $actualResponse
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function isResponseExpected($actualResponse)
    {
        $this->checkEntityEqual($this->getExpectedResponse(), $actualResponse);
    }

    /**
     * @return Assert|FunctionalTester
     */
    protected function getFunctionalTester()
    {
        return $this->functionalTester;
    }

    /**
     * @return mixed
     */
    protected function getExpectedResponse()
    {
        return $this->expectedResponse;
    }

    /**
     * @return bool
     */
    protected function isShouldBeSame(): bool
    {
        return $this->shouldBeSame;
    }

    /**
     * @param $expected
     * @param $actual
     * @param string $index
     * @throws Exception
     * @throws InvalidArgumentException
     */
    protected function checkEntityEqual($expected, $actual, $index = '')
    {
        if (is_array($expected)) {
            $this->checkArrayItem($expected, $actual, $index);
        } elseif ($this->isShouldBeSame()) {
            $this->getFunctionalTester()->assertSame($expected, $actual, sprintf('array index %s', $index));
        } else {
            $this->getFunctionalTester()->assertEquals($expected, $actual, sprintf('array index %s', $index));
        }
    }

    /**
     * @param $expected
     * @param $actual
     * @param $index
     * @throws Exception
     * @throws InvalidArgumentException
     */
    protected function checkArrayItem($expected, $actual, $index)
    {
        ksort($expected);
        ksort($actual);

        $this->getFunctionalTester()->assertSame(
            array_keys($expected),
            array_keys($actual),
            sprintf('%s index array keys does not match', $index)
        );

        foreach ($expected as $key => $expectedValue) {
            $this->getFunctionalTester()->assertArrayHasKey(
                $key,
                $actual,
                sprintf('array at %s index should have %s key value', $index, $key)
            );

            if ($expectedValue instanceof AbstractArrayElement) {
                $expectedValue->checkElement($actual[$key]);

                continue;
            }

            $this->checkEntityEqual($expectedValue, $actual[$key], sprintf('%s.%s', $index, $key));
        }
    }
}
