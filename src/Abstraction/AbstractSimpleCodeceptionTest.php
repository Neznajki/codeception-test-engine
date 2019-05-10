<?php /** @noinspection PhpUndefinedClassInspection */

/**
 * Created by PhpStorm.
 * User: mlocmelis
 * Date: 3/1/19
 * Time: 10:18 AM
 */

namespace Tests\Neznajka\Unit\Abstraction;

use Codeception\Test\Unit;
use Tests\Neznajka\Unit\Traits\CommonAbstractionTrait;
use Tests\Neznajka\Unit\Traits\CommonGetters;
use Tests\Neznajka\Unit\Traits\MockingFeaturesTrait;
use Tests\Neznajka\Unit\Traits\NotPublicParametersTrait;
use Tests\Neznajka\Unit\Traits\PredefinedTestCollectionTrait;
use Tests\Neznajka\Unit\Traits\RandomGenerationTrait;
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

    /**
     * AbstractSimpleCodeceptionTest constructor.
     * @param string|null $name
     * @param array $data
     * @param string $dataName
     */
    public function __construct(string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
    }
}
