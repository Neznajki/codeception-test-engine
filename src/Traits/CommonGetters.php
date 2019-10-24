<?php
/**
 * Created by PhpStorm.
 * User: mlocmelis
 * Date: 3/1/19
 * Time: 11:01 AM
 */

namespace Tests\Neznajka\Codeception\Engine\Traits;

use PHPUnit\Framework\MockObject\MockObject;
use Psr\Container\ContainerInterface;
use Tests\Neznajka\Codeception\Engine\Traits\CodeceptionClass\UnitTrait;

/**
 * Class CommonGetters
 * @package Tests\Neznajka\Codeception\Engine\Traits
 *
 *
 */
trait CommonGetters
{
    use UnitTrait;

    /**
     * @return ContainerInterface|MockObject
     */
    protected function getContainerMock()
    {
        return $this->createMock(ContainerInterface::class);
    }

    /**
     * @param array $data
     * @return array
     */
    protected function getConsucativeCallsFromArray(array $data): array
    {
        $result = [];

        foreach ($data as $record) {
            $result[] = [$record];
        }

        return $result;
    }
}
