<?php
/**
 * Created by PhpStorm.
 * User: mlocmelis
 * Date: 3/1/19
 * Time: 11:37 AM
 */

namespace Tests\Neznajka\Codeception\Engine\Traits\CodeceptionClass;

use Codeception\PHPUnit\TestCase;
use Tests\Neznajka\Codeception\Engine\Traits\PhpUnitClass\TestCaseTrait;

/**
 * Class TestCaseTrait
 * @package Tests\Neznajka\Codeception\Engine\Traits\CodeceptionClass
 * @uses TestCase
 *
 * @method getMetadata()
 * @method setUp()
 * @method _before()
 * @method tearDown()
 * @method _after()
 * @method setExpectedException($exception, $message = null, $code = null))
 * @method getModule($module)
 * @method getCurrent($current)
 * @method getReportFields()
 * @method fetchDependencies()
 * @method handleDependencies()
 */
trait UnitTrait
{
    use TestCaseTrait;
}
