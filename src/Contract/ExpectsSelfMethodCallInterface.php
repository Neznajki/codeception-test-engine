<?php
/**
 * Created by PhpStorm.
 * User: mlocmelis
 * Date: 9/12/18
 * Time: 4:07 PM
 */

namespace Tests\Neznajka\Codeception\Engine\Contract;


/**
 * Interface ExpectsSelfMethodCall
 * @package Tests\Neznajka\Codeception\Engine\Contract
 * @codeCoverageIgnore
 */
interface ExpectsSelfMethodCallInterface
{
    /**
     * @return string
     */
    public function getGetterMethodName(): string ;
}
