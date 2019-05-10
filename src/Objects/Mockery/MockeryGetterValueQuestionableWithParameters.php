<?php
/**
 * Created by PhpStorm.
 * User: mlocmelis
 * Date: 9/13/18
 * Time: 3:21 PM
 */

namespace Tests\Neznajka\Unit\Objects\Mockery;

use Mockery\MockeryGetterValueQuestionable;
use Tests\Neznajka\Unit\Contract\HaveParametersInterface;
use Tests\Neznajka\Unit\Contract\QuestionInterface;


/**
 * Class MockeryGetterValueQuestionable
 * @package Tests\Neznajka\Unit\Objects\Mockery
 */
class MockeryGetterValueQuestionableWithParameters extends MockeryGetterValueQuestionable implements HaveParametersInterface
{
    /** @var array */
    protected $arguments;
    /** @var array */
    protected $preparedArguments;

    /**
     * MockeryGetterValueQuestionableWithParameters constructor.
     * @param string $getterMethodName
     * @param $expectedResponse
     * @param QuestionInterface $question
     * @param mixed ...$arguments
     */
    public function __construct(
        string $getterMethodName,
        $expectedResponse,
        QuestionInterface $question,
        ... $arguments
    ) {
        $this->arguments = $arguments;

        parent::__construct($getterMethodName, $expectedResponse, $question);
    }

    /**
     * @return array
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }

    /**
     * @param array $arguments
     */
    public function setPreparedArguments(array $arguments)
    {
        $this->preparedArguments = $arguments;
    }

    /**
     * @return array
     */
    public function getPreparedArguments(): array
    {
        return $this->preparedArguments;
    }
}
