<?php
/**
 * Created by PhpStorm.
 * User: mlocmelis
 * Date: 9/12/18
 * Time: 4:09 PM
 */

namespace Tests\Neznajka\Codeception\Engine\Contract;


/**
 * Interface ExpectsWith
 * @package Tests\Neznajka\Codeception\Engine\Contract
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
