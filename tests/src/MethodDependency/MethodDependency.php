<?php
declare(strict_types=1);

namespace Tests\TestsEngine\Code\MethodDependency;

/**
 * Class MethodDependency
 * @package Tests\TestsEngine\Code\MethodDependency
 */
class MethodDependency
{

    /**
     * MethodDependency constructor.
     * @param TestDependency $dependency
     */
    public function __construct(TestDependency $dependency)
    {
    }
}
