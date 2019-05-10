<?php
/**
 * Created by PhpStorm.
 * User: mlocmelis
 * Date: 9/12/18
 * Time: 4:09 PM
 */

namespace Tests\Neznajka\Unit\Contract;


/**
 * Interface ExpectsWith
 * @package Tests\Neznajka\Unit\Contract
 * @codeCoverageIgnore
 */
interface HaveParametersInterface
{

    /**
     * @return array
     */
    public function getArguments(): array;

    /**
     * @param array $arguments
     * @return mixed
     */
    public function setPreparedArguments(array $arguments);

    /**
     * @return array
     */
    public function getPreparedArguments(): array ;
}
