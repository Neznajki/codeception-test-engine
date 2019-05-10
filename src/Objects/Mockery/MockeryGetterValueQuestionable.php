<?php
/**
 * Created by PhpStorm.
 * User: mlocmelis
 * Date: 9/13/18
 * Time: 3:21 PM
 */

namespace Mockery;

use Tests\Neznajka\Unit\Contract\QuestionableInterface;
use Tests\Neznajka\Unit\Contract\QuestionInterface;
use Tests\Neznajka\Unit\Objects\Mockery\MockeryGetterValue;


/**
 * Class MockeryGetterValueQuestionable
 * @package Mockery
 */
class MockeryGetterValueQuestionable extends MockeryGetterValue implements QuestionableInterface
{
    /** @var QuestionInterface */
    protected $question;

    /**
     * MockeryGetterValueQuestionable constructor.
     * @param string $getterMethodName
     * @param $expectedResponse
     * @param QuestionInterface $question
     */
    public function __construct(string $getterMethodName, $expectedResponse, QuestionInterface $question)
    {
        $this->question = $question;
        parent::__construct($getterMethodName, $expectedResponse);
    }

    /**
     * @return QuestionInterface
     */
    public function getQuestion(): QuestionInterface
    {
        return $this->question;
    }
}
