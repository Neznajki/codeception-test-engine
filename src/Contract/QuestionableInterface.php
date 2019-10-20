<?php
/**
 * Created by PhpStorm.
 * User: mlocmelis
 * Date: 9/12/18
 * Time: 3:16 PM
 */

namespace Tests\Neznajka\Codeception\Engine\Contract;


/**
 * Interface Questionable
 * @package Tests\Neznajka\Codeception\Engine\Contract
 * @codeCoverageIgnore
 */
interface QuestionableInterface
{
    /**
     * @return QuestionInterface
     */
    public function getQuestion(): QuestionInterface;
}
