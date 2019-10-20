<?php /** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */
declare(strict_types=1);

use Tests\Neznajka\Codeception\Engine\Traits\SymfonyKernelTrait;
use Tests\Neznajka\Codeception\Engine\Traits\RandomGenerationTrait;
use Tests\TestsEngine\Code\MethodDependency\MethodDependency;
use Tests\TestsEngine\Code\MethodDependency\TestDependency;
use Tests\TestsEngine\Code\MethodDependency\TestSubject;

/**
 * Class TestDependencyInjectionCest
 */
class TestDependencyInjectionCest
{

    use SymfonyKernelTrait, RandomGenerationTrait;

    /**
     * @param FunctionalTester $I
     */
    public function testGetMethodArguments(FunctionalTester $I)
    {
        /** @var TestSubject $class */
        $class               = $this->createClass(TestSubject::class);
        $secondCoolParamMock = $this->getInt();
        $coolParamMock       = $this->getString();
        $arguments           = $this->getMethodArguments(
            TestSubject::class,
            'methodRequired',
            ['secondCoolParam' => $secondCoolParamMock, 'coolParam' => $coolParamMock]
        );

        $I->assertSame(
            [
                $this->createClass(MethodDependency::class),
                $coolParamMock,
                $secondCoolParamMock
            ],
            $class->methodRequired(... $arguments)
        );
    }

    /**
     * @param FunctionalTester $I
     */
    public function testCreateClass(FunctionalTester $I)
    {
        /** @var TestSubject $class */
        $class = $this->createClass(TestSubject::class);
        $I->assertEquals(
            new TestDependency(),
            $class->getTestDependency()
        );
    }
}
