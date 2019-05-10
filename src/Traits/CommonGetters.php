<?php
/**
 * Created by PhpStorm.
 * User: mlocmelis
 * Date: 3/1/19
 * Time: 11:01 AM
 */

namespace Tests\Neznajka\Unit\Traits;

use PHPUnit\Framework\MockObject\MockObject;
use Psr\Container\ContainerInterface;
use AspectMock\Test;
use Tests\Neznajka\Unit\Traits\CodeceptionClass\UnitTrait;

/**
 * Class CommonGetters
 * @package Tests\Neznajka\Unit\Traits
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
     * @return CoolSecretClass|MockObject
     * @throws \Exception
     */
    protected function getApplicationMock()
    {
        if (! class_exists('CoolSecretClass')) {
            throw new \LogicException("CoolSecretClass class not found this is not application");
        }

        $result = $this->createMock('CoolSecretClass');

        Test::double('CoolSecretClass', ['getInstance' => $result]);
        Test::func($this->getWorkingClassNameSpace(), 'app', $result);

        return $result;
    }
}
