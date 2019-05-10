<?php
/**
 * Created by PhpStorm.
 * User: mlocmelis
 * Date: 9/12/18
 * Time: 3:17 PM
 */

namespace Tests\Neznajka\Unit\Contract;


/**
 * Interface Question
 * @package Tests\Neznajka\Unit\Contract
 * @codeCoverageIgnore
 */
interface QuestionInterface
{
    /**
     * @return int
     */
    public function getCallTimes(): int;

    /**
     * @return mixed
     */
    public function getExpectedValue();

    /**
     * @return mixed
     */
    public function getReversedExpectedValue();

    /**
     * @param bool $itemRequired
     * @return void
     */
    public function setItemRequired(bool $itemRequired);

    /**
     * @return bool
     */
    public function isItemRequired(): bool;
}
