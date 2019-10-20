<?php
declare(strict_types=1);

namespace Tests\TestsEngine\Code\MethodDependency;

/**
 * Class TestSubject
 * @package Tests\TestsEngine\Code\InjectionTest
 */
class TestSubject
{

    /** @var TestDependency */
    protected $testDependency;

    /**
     * TestSubject constructor.
     * @param TestDependency $testDependency
     */
    public function __construct(TestDependency $testDependency)
    {
        $this->testDependency = $testDependency;
    }

    /**
     * @return TestDependency
     */
    public function getTestDependency(): TestDependency
    {
        return $this->testDependency;
    }

    /**
     * @param MethodDependency $methodDependency
     * @param string $coolParam
     * @param int $secondCoolParam
     * @return array
     */
    public function methodRequired(MethodDependency $methodDependency, string $coolParam, int $secondCoolParam): array
    {
        return func_get_args();
    }
}
