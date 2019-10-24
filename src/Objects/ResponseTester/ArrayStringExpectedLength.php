<?php
declare(strict_types=1);

namespace Tests\Neznajka\Codeception\Engine\Objects\ResponseTester;

use InvalidArgumentException;

/**
 * Class ArrayStringExpectedLength
 * @package Tests\Neznajka\Codeception\Engine\Objects\ResponseTester
 */
class ArrayStringExpectedLength extends AbstractArrayElement
{
    /** @var int */
    protected $stringLength;

    /**
     * ArrayStringExpectedLength constructor.
     * @param int $stringLength
     */
    public function __construct(int $stringLength)
    {
        $this->stringLength = $stringLength;
    }

    /**
     * @param $item
     * @return bool
     * @throws InvalidArgumentException
     */
    public function checkElement($item): bool
    {
        if (! is_string($item)) {
            throw new InvalidArgumentException(
                'item provided for length check is not string'
            );
        }

        $stringLength = strlen($item);
        if ($stringLength !== $this->stringLength) {
            throw new InvalidArgumentException(
                sprintf('incorrect string length %s expected got %s', $this->stringLength, $stringLength)
            );
        }

        return true;
    }
}
