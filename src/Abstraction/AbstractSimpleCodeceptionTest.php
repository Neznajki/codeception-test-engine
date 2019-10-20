<?php /** @noinspection PhpUndefinedClassInspection */

/**
 * Created by PhpStorm.
 * User: mlocmelis
 * Date: 3/1/19
 * Time: 10:18 AM
 */

namespace Tests\Neznajka\Codeception\Engine\Abstraction;

use Codeception\Test\Unit;
use Tests\Neznajka\Codeception\Engine\Traits\CommonAbstractionTrait;
use Tests\Neznajka\Codeception\Engine\Traits\CommonGetters;
use Tests\Neznajka\Codeception\Engine\Traits\MockingFeaturesTrait;
use Tests\Neznajka\Codeception\Engine\Traits\NotPublicParametersTrait;
use Tests\Neznajka\Codeception\Engine\Traits\PredefinedTestCollectionTrait;
use Tests\Neznajka\Codeception\Engine\Traits\RandomGenerationTrait;
use UnitTester;

/**
 * Class AbstractSimpleTest
 * @package Test
 */
abstract class AbstractSimpleCodeceptionTest extends Unit
{
    use
        CommonAbstractionTrait,
        CommonGetters,
        NotPublicParametersTrait,
        MockingFeaturesTrait,
        RandomGenerationTrait,
        PredefinedTestCollectionTrait;

    /**
     * @var UnitTester
     */
    protected $tester;
}
