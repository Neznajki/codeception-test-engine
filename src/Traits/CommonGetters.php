<?php
/**
 * Created by PhpStorm.
 * User: mlocmelis
 * Date: 3/1/19
 * Time: 11:01 AM
 */

namespace Tests\Neznajka\Codeception\Engine\Traits;

use /** @noinspection PhpUndefinedClassInspection */
    /** @noinspection PhpUndefinedNamespaceInspection */
    Dyninno\Core\Application;
use Exception;
use LogicException;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Container\ContainerInterface;
use AspectMock\Test;
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
    }/** @noinspection PhpUndefinedClassInspection */
    /** @noinspection PhpUndefinedNamespaceInspection */

    /**
     * @return Application|MockObject
     * @throws Exception
     */
    protected function getApplicationMock()
    {
        if (! class_exists('Dyninno\Core\Application')) {
            throw new LogicException("Dyninno\Core\Application class not found this is not application");
        }

        $result = $this->createMock('Dyninno\Core\Application');

        Test::double('Dyninno\Core\Application', ['getInstance' => $result]);
        Test::func($this->getWorkingClassNameSpace(), 'app', $result);

        return $result;
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
