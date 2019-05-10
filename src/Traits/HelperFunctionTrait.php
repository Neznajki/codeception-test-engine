<?php
/**
 * Created by PhpStorm.
 * User: mlocmelis
 * Date: 3/1/19
 * Time: 2:16 PM
 */

namespace Tests\Neznajka\Unit\Traits;

use Exception;
use SplObjectStorage;
use Tests\Neznajka\Unit\Contract\ExpectsSelfMethodCallInterface;
use Tests\Neznajka\Unit\Contract\HaveParametersInterface;
use Tests\Neznajka\Unit\Contract\QuestionInterface;
use Tests\Neznajka\Unit\Objects\Logical\ConsecutiveCalls;
use Tests\Neznajka\Unit\Objects\Mockery\MockeryGetterObject;
use Tests\Neznajka\Unit\Contract\QuestionableInterface;
use Tests\Neznajka\Unit\Objects\Questions\AskSelfUsingMethod;
use Tests\Neznajka\Unit\Traits\CodeceptionClass\UnitTrait;

/**
 * Trait HelperFunctionTrait
 * @package Tests\Neznajka\Unit\Traits
 */
trait HelperFunctionTrait
{
    use UnitTrait;

    /** @var SplObjectStorage */
    protected $preparedMockObjects;
    /** @var SplObjectStorage */
    protected $questionableParentMocks;
    /** @var ConsecutiveCalls */
    protected $consecutiveCalls;

    protected function cleanup(): self
    {
        $this->preparedMockObjects     = new SplObjectStorage();
        $this->questionableParentMocks = new SplObjectStorage();
        $this->consecutiveCalls        = new ConsecutiveCalls();

        return $this;
    }

    /**
     * @return SplObjectStorage
     */
    protected function getPreparedMockObjects(): SplObjectStorage
    {
        return $this->preparedMockObjects;
    }

    /**
     * @return SplObjectStorage
     */
    protected function getQuestionableParentMocks(): SplObjectStorage
    {
        return $this->questionableParentMocks;
    }

    /**
     * @return ConsecutiveCalls
     */
    protected function getConsecutiveCalls(): ConsecutiveCalls
    {
        return $this->consecutiveCalls;
    }

    /**
     * @param MockeryGetterObject|mixed[] $data
     * @return static
     */
    protected function createAllMockedObjectsRecursive(array $data): self
    {
        foreach ($data as $item) {
            if ($item instanceof MockeryGetterObject) {
                $mock = $this->createMock($item->getResultClassName());
                $this->getPreparedMockObjects()->attach($item, $mock);

                if ($item instanceof QuestionableInterface) {
                    $this->getQuestionableParentMocks()->attach($item->getQuestion(), $mock);
                }
            }

            if ($item instanceof HaveParametersInterface) {
                $this->createAllMockedObjectsRecursive($item->getArguments());
            }

            if ($item instanceof QuestionableInterface) {
                $this->createAllMockedObjectsRecursive([$item->getQuestion()]);
            }
        }

        return $this;
    }

    /**
     * @param ExpectsSelfMethodCallInterface|mixed[] $data
     * @return array
     */
    protected function getAllExpectedClassMethodsRecursive(array $data)
    {
        $result = [];

        foreach ($data as $item) {
            if ($item instanceof ExpectsSelfMethodCallInterface) {
                $result[] = $item->getGetterMethodName();
            }

            if ($item instanceof HaveParametersInterface) {
                $result = array_merge($result, $this->getAllExpectedClassMethodsRecursive($item->getArguments()));
            }

            if ($item instanceof QuestionableInterface) {
                $result = array_merge($result, $this->getAllExpectedClassMethodsRecursive([$item->getQuestion()]));
            }
        }

        $result = array_unique($result);

        return $result;
    }

    /**
     * @param QuestionableInterface|mixed[] $data
     * @param bool $required
     */
    protected function setQuestionExpectationsRecursive(array $data, bool $required)
    {
        foreach ($data as $item) {
            if ($item instanceof QuestionableInterface) {
                $item->getQuestion()->setItemRequired($required);

                $this->setQuestionExpectationsRecursive([$item->getQuestion()], $required);
            }

            if ($item instanceof HaveParametersInterface) {
                $this->setQuestionExpectationsRecursive($item->getArguments(), $required);
            }
        }
    }

    /**
     * @param QuestionableInterface $questionableObject
     * @return bool
     * @throws Exception
     */
    protected function registerQuestionableMockeryObjectsQuestion(
        QuestionableInterface $questionableObject
    ) {
        if (! $questionableObject->getQuestion() instanceof AskSelfUsingMethod) {
            throw new Exception("not implemented logic for other question");
        }

        $question = $questionableObject->getQuestion();
        if ($questionableObject instanceof MockeryGetterObject) {
            if ($question->getCallTimes() > 1 || $questionableObject->getCallTimes() > 1) {
                throw new Exception("this case is not handled");
            }
        }

        if ($question->isItemRequired()) {
            $this->_registerQuestionableMockeryObjectsQuestion(
                $questionableObject->getQuestion(),
                $question->getExpectedValue()
            );

            return true;
        }

        $this->_registerQuestionableMockeryObjectsQuestion(
            $questionableObject->getQuestion(),
            $question->getReversedExpectedValue()
        );

        return false;
    }

    /**
     * @param HaveParametersInterface|mixed[] $data
     * @param null $parent
     * @return array
     */
    protected function prepareArgumentsRecursive(array $data, $parent = null): array
    {
        $result = [];

        foreach ($data as $item) {
            if ($item instanceof HaveParametersInterface) {
                $arguments = $this->prepareArgumentsRecursive($item->getArguments(), $item);

                $item->setPreparedArguments($arguments);
            }

            if ($item instanceof QuestionableInterface) {
                $this->prepareArgumentsRecursive([$item->getQuestion()], $parent);
            }

            if ($item === ConsecutiveCalls::MOCK_INDEX) {
                $result[] = $this->getQuestionableParentMocks()->offsetGet($parent);
                continue;
            }

            if ($item instanceof MockeryGetterObject) {
                $result[] = $this->getPreparedMockObjects()->offsetGet($item);
                continue;
            }

            $result[] = $item;
        }

        return $result;
    }

    /**
     * @param AskSelfUsingMethod|QuestionInterface $question
     * @param mixed $returnValue
     * @codeCoverageIgnore
     */
    private function _registerQuestionableMockeryObjectsQuestion(
        AskSelfUsingMethod $question,
        $returnValue
    ) {
        $this->getConsecutiveCalls()->addSingleConsecutiveMethodCall(
            $question,
            $returnValue
        );
    }
}
