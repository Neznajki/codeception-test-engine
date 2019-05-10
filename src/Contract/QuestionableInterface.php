<?php
/**
 * Created by PhpStorm.
 * User: mlocmelis
 * Date: 9/12/18
 * Time: 3:16 PM
 */

namespace Tests\Neznajka\Unit\Contract;


/**
 * Interface Questionable
 * @package Tests\Neznajka\Unit\Contract
 * @codeCoverageIgnore
 */
interface QuestionableInterface
{
    /**
     * @return QuestionInterface
     */
    public function getQuestion(): QuestionInterface;
}
