<?php
declare(strict_types=1);

namespace Tests\Neznajka\Codeception\Engine\Objects\ResponseTester;

/**
 * Class ArrayUnknownValue
 * @package Tests\Neznajka\Codeception\Engine\Objects
 */
class ArrayUnknownValue extends AbstractArrayElement
{

    /**
     * @param $item
     * @return bool
     */
    public function checkElement($item): bool
    {
        return true;
    }
}
