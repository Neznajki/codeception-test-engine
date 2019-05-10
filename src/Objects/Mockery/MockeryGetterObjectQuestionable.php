<?php
/**
 * Created by PhpStorm.
 * User: mlocmelis
 * Date: 9/12/18
 * Time: 2:35 PM
 */

namespace Tests\Neznajka\Unit\Objects\Mockery;

use Tests\Neznajka\Unit\Contract\QuestionableInterface;
use Tests\Neznajka\Unit\Contract\QuestionInterface;
use Tests\Neznajka\Unit\Objects\Questions\AskSelfUsingMethod;


/**
 * Class MockeryGetterObjectQuestionable
 * @package Tests\Neznajka\Unit\Objects
 */
class MockeryGetterObjectQuestionable extends MockeryGetterObject implements QuestionableInterface
{
    /** @var AskSelfUsingMethod|QuestionInterface */
    protected $question;

    /**
     * MockeryGetterObjectQuestionable constructor.
     * @param string $getterMethodName
     * @param string $resultClassName
     * @param int $callTimes
     * @param AskSelfUsingMethod $question
     */
    public function __construct(string $getterMethodName, string $resultClassName, int $callTimes, AskSelfUsingMethod $question)
    {
        $this->question = $question;

        parent::__construct($getterMethodName, $resultClassName, $callTimes);
    }

    /**
     * @return QuestionInterface
     */
    public function getQuestion(): QuestionInterface
    {
        return $this->question;
    }
}
