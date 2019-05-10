<?php /** @noinspection PhpUnhandledExceptionInspection */

/**
 * Created by PhpStorm.
 * User: mlocmelis
 * Date: 3/1/19
 * Time: 2:33 PM
 */

namespace Tests\TestsEngine\unit\Abstraction;

use Codeception\Test\Unit;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\Neznajka\Unit\Abstraction\AbstractSimpleCodeceptionTest;
use Tests\Neznajka\Unit\Traits\CommonAbstractionTrait;
use Tests\Neznajka\Unit\Traits\NotPublicParametersTrait;
use Tests\Neznajka\Unit\Traits\PredefinedTestCollectionTrait;
use Tests\Neznajka\Unit\Traits\RandomGenerationTrait;

/**
 * Class TestAbstractSimpleCodeceptionTestTest
 * @package Tests\TestsEngine\unit\Abstraction
 * @method AbstractSimpleCodeceptionTest|MockObject getWorkingClass(... $mockedMethods)
 */
class TestAbstractSimpleCodeceptionTestTest extends Unit
{
    use
        CommonAbstractionTrait,
        PredefinedTestCollectionTrait,
        RandomGenerationTrait,
        NotPublicParametersTrait;

    /**
     *
     */
    public function test___construct()
    {
        $this->wantToTestThisMethod();
        $class = $this->getWorkingClass('setName');

        $name     = $this->getString();
        $data     = $this->getArray();
        $dataName = $this->getString();

        $this->runConstructorTest($class, $name, $data, $dataName);

        $this->assertTrue(true);
    }

    /**
     * @return string
     */
    protected function getWorkingClassName(): string
    {
        return AbstractSimpleCodeceptionTest::class;
    }
}
