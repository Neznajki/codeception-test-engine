<?php
/** @noinspection PhpUnhandledExceptionInspection */
declare(strict_types=1);

namespace Tests\TestsEngine\functional\Traits;

use AspectMock\Test;
use Exception;
use FunctionalTester;
use LogicException;
use Tests\Neznajka\Codeception\Engine\Abstraction\AbstractFunctionalSymfonyCodeceptionTest;
use Tests\Neznajka\Codeception\Engine\Objects\CallableAction;
use Tests\Neznajka\Codeception\Engine\Objects\MysqlResultMock;
use Tests\Neznajka\Codeception\Engine\Traits\QueryMatcherTrait;

/**
 * Class QueryMatcherTraitCest
 * @package Tests\TestsEngine\functional\Traits
 */
class QueryMatcherTraitCest extends AbstractFunctionalSymfonyCodeceptionTest
{
    use QueryMatcherTrait {
        QueryMatcherTrait::_after as __after;
    }

    private $expectException= false;

    public function _after(FunctionalTester $I)
    {
        $tmpActions = $this->afterActionCollection;
        $this->__after($I);

        if ($this->expectException) {
            $this->expectException = false;
            $this->afterActionCollection = $tmpActions;
            Test::clean();

            $self = $this;
            $I->expectException(
                LogicException::class,
                function () use ($self, $I) {
                    $self->_after($I);
                }
            );
        }
    }

    /**
     * @param FunctionalTester $I
     * @throws LogicException
     * @depends testQueryMatcherException
     */
    public function testQueryMatcherWork(FunctionalTester $I)
    {
        $expectingQueryMock  = $this->getString();
        $queryMatcherService = $this->getQueryMatcher();
        $expectingResult                = $this->getArray();
        $queryMatcherService->addQueryResult(
            $expectingQueryMock,
            new MysqlResultMock(
                $expectingResult
            )
        );

        $queryResult = $queryMatcherService->getQueryResult($expectingQueryMock)->fetch_array();
        $I->assertSame(
            $expectingResult,
            $queryResult
        );
    }

    /**
     * @param FunctionalTester $I
     * @throws LogicException
     * @throws Exception
     */
    public function testQueryMatcherException(FunctionalTester $I)
    {
        $expectingQueryMock  = $this->getString();
        $queryMatcherService = $this->getQueryMatcher();
        $queryMatcherService->addQueryResult(
            $expectingQueryMock,
            new MysqlResultMock(
                $this->getArray()
            )
        );

        Test::double(CallableAction::class, [
            'dispatchAction' => function(array $params) use ($I, $queryMatcherService) {
                /** @noinspection PhpUndefinedFieldInspection */
                $call = $this->execution;
                if (! is_array($call)) {
                    return ;//ignore closure
                }

                $I->assertSame([
                    $queryMatcherService,
                    'isExpectationsMet'
                ], $call);
                $I->assertSame([], $params);
            }
        ]);

        $this->expectException = true;
    }

    protected function overrideQueryMatcherMethodCalls()
    {
        //not needed here
    }
}
