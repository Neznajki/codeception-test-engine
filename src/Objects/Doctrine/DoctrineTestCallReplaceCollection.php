<?php
declare(strict_types=1);

namespace Tests\Neznajka\Codeception\Engine\Objects\Doctrine;

use AspectMock\Test;
use Exception;
use RuntimeException;

/**
 * Class DoctrineTestCallReplaceCollection
 * @package Tests\Neznajka\Codeception\Engine\Objects\Doctrine
 */
class DoctrineTestCallReplaceCollection
{
    /** @var AbstractDoctrineTestCall[]  */
    protected $testCalls = [];
    /** @var array */
    protected $functionCallsMocked = [];

    /**
     * @param AbstractDoctrineTestCall $callReplace
     * @throws Exception
     */
    public function addCallReplace(AbstractDoctrineTestCall $callReplace)
    {
        $this->testCalls[] = $callReplace;
        $this->initFunctionMock($callReplace);
    }

    /** @noinspection PhpUndefinedClassInspection */
    /**
     * @param object $class
     * @param string $function
     * @param array $arguments
     * @return Entity|Entity[]
     * @throws RuntimeException
     */
    public function callExecuted($class, string $function, array $arguments)
    {
        foreach ($this->testCalls as $call) {
            if ($call->isRequiredCall($class, $function, $arguments)) {
                return $call->getResponse();
            }
        }

        throw new RuntimeException("could not find EntityRepository method call");
    }

    /**
     * @param AbstractDoctrineTestCall $callReplace
     * @throws Exception
     */
    protected function initFunctionMock(AbstractDoctrineTestCall $callReplace)
    {
        $function = $callReplace->getFunction();
        if (empty($this->functionCallsMocked[$function])) {
            $self = $this;

            Test::double($callReplace->getTargetClassName(), [
                $function => function() use ($self, $function) {
                    return $self->callExecuted($this, $function, func_get_args());
                }
            ]);
        }
    }
}
