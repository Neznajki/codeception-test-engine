<?php
declare(strict_types=1);

namespace Tests\Neznajka\Codeception\Engine\Traits;

use FunctionalTester;
use Tests\Neznajka\Codeception\Engine\Objects\CallableAction;
use Throwable;

/**
 * Class BeforeResolverTrait
 * @package Tests\Neznajka\Codeception\Engine\Traits
 */
trait BeforeResolverTrait
{
    /** @var CallableAction[] */
    protected $beforeActionCollection = [];

    /**
     * @param FunctionalTester $I
     * @throws Throwable
     */
    public function _before(FunctionalTester $I)
    {
        foreach ($this->beforeActionCollection as $action) {
            try {
                $action->handle($I);
            } catch (Throwable $exception) {
                $this->afterActionCollection = [];
                throw $exception;
            }
        }

        $this->beforeActionCollection = [];
    }

    /**
     * @param CallableAction $beforeAction
     */
    protected function addBeforeAction(CallableAction $beforeAction)
    {
        $this->beforeActionCollection[] = $beforeAction;
    }
}
