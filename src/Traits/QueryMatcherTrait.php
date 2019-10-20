<?php
declare(strict_types=1);

namespace Tests\Neznajka\Codeception\Engine\Traits;


use Tests\Neznajka\Codeception\Engine\Objects\CallableAction;
use Tests\Neznajka\Codeception\Engine\Service\QueryMatcherService;

trait QueryMatcherTrait
{
    use AfterResolverTrait, BeforeResolverTrait;

    /** @var QueryMatcherService */
    protected $queryMatcher;

    /**
     * @return QueryMatcherService
     */
    protected function getQueryMatcher(): QueryMatcherService
    {
        if ($this->queryMatcher === null) {
            $this->initQueryMatcher();
        }

        return $this->queryMatcher;
    }

    /**
     *
     */
    private function initQueryMatcher()
    {
        $queryMatcher       = new QueryMatcherService();
        $this->queryMatcher = $queryMatcher;

        $this->overrideQueryMatcherMethodCalls();

        $this->addAfterAction(new CallableAction([$this->queryMatcher, 'isExpectationsMet']));
        $self = $this;

        $this->addBeforeAction(
            new CallableAction(
                function () use ($self) {
                    $self->queryMatcher = null;
                }
            )
        );
    }

    abstract protected function overrideQueryMatcherMethodCalls();

}
