<?php
/**
 * Created by PhpStorm.
 * User: mlocmelis
 * Date: 9/13/18
 * Time: 11:07 AM
 */

namespace Tests\Neznajka\Codeception\Engine\Contract;


/**
 * Interface HaveConsecutiveMethodCalls
 * @package Tests\Neznajka\Codeception\Engine\Contract
 * @codeCoverageIgnore
 */
interface HaveConsecutiveMethodCallsInterface
{

    /**
     * @return string
     */
    public function getGetterMethodName(): string;
}
