<?php
/**
 * Created by PhpStorm.
 * User: mlocmelis
 * Date: 9/12/18
 * Time: 4:07 PM
 */

namespace Tests\Neznajka\Unit\Contract;


/**
 * Interface ExpectsSelfMethodCall
 * @package Tests\Neznajka\Unit\Contract
 * @codeCoverageIgnore
 */
interface ExpectsSelfMethodCallInterface
{
    /**
     * @return string
     */
    public function getGetterMethodName(): string ;
}
