<?php
declare(strict_types=1);

//use Dyninno\DependencyInjection\Exceptions\AbstractDependencyInjectionException;
//use Dyninno\DependencyInjection\Exceptions\ArgumentResolverException;
//use Tests\Neznajka\Codeception\Engine\Traits\DependencyInjectionTrait;
//use Tests\Neznajka\Codeception\Engine\Traits\RandomGenerationTrait;
//use Tests\TestsEngine\Code\MethodDependency\MethodDependency;
//use Tests\TestsEngine\Code\MethodDependency\TestDependency;
//use Tests\TestsEngine\Code\MethodDependency\TestSubject;

/**
 * Class TestDependencyInjectionCest
 */
class TestDependencyInjectionCest
{

//    use DependencyInjectionTrait, RandomGenerationTrait;
//
//    /**
//     * @param FunctionalTester $I
//     * @throws ReflectionException
//     * @throws RuntimeException
//     * @throws AbstractDependencyInjectionException
//     * @throws ArgumentResolverException
//     */
//    public function testGetMethodArguments(FunctionalTester $I)
//    {
//        /** @var TestSubject $class */
//        $class               = $this->createClass(TestSubject::class);
//        $secondCoolParamMock = $this->getInt();
//        $coolParamMock       = $this->getString();
//        $arguments           = $this->getMethodArguments(
//            TestSubject::class,
//            'methodRequired',
//            ['secondCoolParam' => $secondCoolParamMock, 'coolParam' => $coolParamMock]
//        );
//
//        $I->assertSame(
//            [
//                $this->createClass(MethodDependency::class),
//                $coolParamMock,
//                $secondCoolParamMock
//            ],
//            $class->methodRequired(... $arguments)
//        );
//    }
//
//    /**
//     * @param FunctionalTester $I
//     * @throws AbstractDependencyInjectionException
//     */
//    public function testCreateClass(FunctionalTester $I)
//    {
//        /** @var TestSubject $class */
//        $class = $this->createClass(TestSubject::class);
//        $I->assertEquals(
//            new TestDependency(),
//            $class->getTestDependency()
//        );
//    }
}
