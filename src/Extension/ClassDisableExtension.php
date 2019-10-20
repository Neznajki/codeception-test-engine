<?php


namespace Tests\Neznajka\Codeception\Engine\Extension;


use AspectMock\Test;
use Codeception\Event\SuiteEvent;
use Codeception\Event\TestEvent;
use Codeception\Events;
use Codeception\Extension;
use Exception;
use LogicException;
use ReflectionClass;
use ReflectionException;

/**
 * Class ClassDisableExtension
 * @package Tests\Neznajka\Codeception\Engine\Extension
 */
class ClassDisableExtension extends Extension
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
     * @throws ReflectionException
     * @throws Exception
     */
    public function disableFunctions(
        /** @noinspection PhpUnusedParameterInspection */
        TestEvent $e
    ) {
        $classes = $this->getDisabledClasses();

        foreach ($classes as $className) {
            if (! class_exists($className)) {
                throw new LogicException("{$className} class does not exists");
            }

            $reflection = new ReflectionClass($className);

            $methods         = $reflection->getMethods();
            $disabledMethods = [];

            foreach ($methods as $reflectionMethod) {
                $methodName = $reflectionMethod->getName();

                $disabledMethods[$methodName] = function (
                    /** @noinspection PhpUnusedParameterInspection */
                    ... $arguments
                ) use ($className, $methodName) {
                    throw new LogicException("{$className}::{$methodName} is disabled by class disable extension");
                };
            }

            Test::double($className, $disabledMethods);
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
    protected function getDisabledClasses(): array
    {
        $settings = $this->getSuiteEvent()->getSettings();

        if (! array_key_exists('disabled_classes', $settings)) {
            throw new LogicException("disabled_classes is required to detect extension settings");
        }

        return $settings['disabled_classes'];
    }
}
