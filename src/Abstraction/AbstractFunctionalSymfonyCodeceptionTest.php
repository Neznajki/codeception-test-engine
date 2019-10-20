<?php
declare(strict_types=1);

namespace Tests\Neznajka\Codeception\Engine\Abstraction;

use Tests\Neznajka\Codeception\Engine\Traits\ContainerUsageTrait;
use Tests\Neznajka\Codeception\Engine\Traits\NotPublicParametersTrait;
use Tests\Neznajka\Codeception\Engine\Traits\PredefinedTestCollectionTrait;
use Tests\Neznajka\Codeception\Engine\Traits\RandomGenerationTrait;

/**
 * Class AbstractFunctionalCodeceptionTest
 * @package Tests\Neznajka\Codeception\Engine\Abstraction
 */
abstract class AbstractFunctionalSymfonyCodeceptionTest
{
    use RandomGenerationTrait,
        NotPublicParametersTrait,
        PredefinedTestCollectionTrait,
        ContainerUsageTrait;
}
