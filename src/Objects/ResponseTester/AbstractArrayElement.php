<?php
declare(strict_types=1);

namespace Tests\Neznajka\Codeception\Engine\Objects\ResponseTester;

use InvalidArgumentException;

/**
 * Class AbstractArrayElement
 * @package Tests\Neznajka\Codeception\Engine\Objects\ResponseTester
 */
abstract class AbstractArrayElement
{
    /**
     * @param $item
     * @return bool
     * @throws InvalidArgumentException
     */
    abstract public function checkElement($item): bool;
}
