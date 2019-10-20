<?php
declare(strict_types=1);

namespace Tests\Neznajka\Codeception\Engine\Traits;

use FunctionalTester;
use Tests\Neznajka\Codeception\Engine\Objects\CallableAction;
use Throwable;

/**
 * Class AfterResolverTrait
 * @package Tests\Neznajka\Codeception\Engine\Traits
 */
trait AfterResolverTrait
{
    /** @var CallableAction[] */
    protected $afterActionCollection = [];

    /**
     * @param FunctionalTester $I
     * @throws Throwable
     */
    public function _after(FunctionalTester $I)
    {
        foreach ($this->afterActionCollection as $action) {
            try {
                $action->handle($I);
            } catch (Throwable $exception) {
                $this->afterActionCollection = [];
                throw $exception;
            }
        }

        $this->afterActionCollection = [];
    }

    /**
     * @param CallableAction $afterAction
     */
    protected function addAfterAction(CallableAction $afterAction)
    {
        $this->afterActionCollection[] = $afterAction;
    }
}
