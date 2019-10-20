<?php


namespace Tests\Neznajka\Codeception\Engine\Extension;


use AspectMock\Test;
use Codeception\Event\SuiteEvent;
use Codeception\Event\TestEvent;
use Codeception\Events;
use Codeception\Extension;
use Exception;
use LogicException;

/**
 * Class MethodDisableExtension
 * @package Tests\Neznajka\Codeception\Engine\Extension
 */
class MethodDisableExtension extends Extension
{
    /** @var TestEvent */
    protected $testEvent;
    /** @var SuiteEvent */
    protected $suiteEvent;

    /**
     * @var array
     */
    public static $events = [
        Events::SUITE_INIT  => 'setSuiteEvent',
        Events::TEST_BEFORE => 'disableFunctions',
    ];


    /**
     * @param TestEvent $e
     * @return bool
     * @throws LogicException
     * @throws Exception
     */
    public function disableFunctions(
        /** @noinspection PhpUnusedParameterInspection */
        TestEvent $e
    ) {
        $methods = $this->getDisabledMethods();

        foreach ($methods as $methodData) {
            if (! class_exists($methodData[0])) {
                throw new LogicException("{$methodData[0]} class does not exists");
            }

            Test::double(
                $methodData[0],
                [
                    $methodData[1] => function (
                        /** @noinspection PhpUnusedParameterInspection */
                        ... $arguments
                    ) use ($methodData) {
                        throw new LogicException("{$methodData[0]}::{$methodData[1]} is disabled by method disable extension");
                    },
                ]
            );
        }

        return true;
    }

    /**
     * @return SuiteEvent
     */
    public function getSuiteEvent(): SuiteEvent
    {
        return $this->suiteEvent;
    }

    /**
     * @param SuiteEvent $suiteEvent
     */
    public function setSuiteEvent(SuiteEvent $suiteEvent)
    {
        $this->suiteEvent = $suiteEvent;
    }

    /**
     * @return TestEvent
     */
    public function getTestEvent(): TestEvent
    {
        return $this->testEvent;
    }

    /**
     * @param TestEvent $testEvent
     * @return $this
     */
    public function setTestEvent(TestEvent $testEvent)
    {
        $this->testEvent = $testEvent;

        return $this;
    }

    /**
     * @return array
     * @throws LogicException
     */
    protected function getDisabledMethods(): array
    {
        $settings = $this->getSuiteEvent()->getSettings();

        if (! array_key_exists('disabled_methods', $settings)) {
            throw new LogicException("disabled_methods is required to detect extension settings");
        }

        return $settings['disabled_methods'];
    }
}
