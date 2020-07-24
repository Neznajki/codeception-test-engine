<?php
declare(strict_types=1);

namespace Tests\Neznajka\Codeception\Engine\Traits;


use Tests\Neznajka\Codeception\Engine\Implementations\TestContainer;

trait ContainerUsageTrait
{
//    use DependencyInjectionTrait;

    /** @var TestContainer  */
    protected $container;

    /**
     * @return TestContainer
     */
    protected function getContainer()
    {
        if ($this->container === null) {
            $this->container = new TestContainer();
        }

        return $this->container;
    }
}
