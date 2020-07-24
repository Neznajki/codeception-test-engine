<?php
declare(strict_types=1);

namespace Tests\Neznajka\Codeception\Engine\Service;

use LogicException;
use RuntimeException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Tests\Neznajka\Codeception\Engine\ValueObject\EventDispatcherProxyData;

/**
 * Class EventDispatcherChecker
 * @package Tests\Neznajka\Codeception\Engine\Service
 */
class EventDispatcherProxyService implements EventDispatcherInterface, EventSubscriberInterface
{
    /** @var EventDispatcherProxyData[] */
    protected $eventQueueCollection = [];
    /** @var bool */
    protected $checked = false;

    /**
     *
     */
    public function failScenario()
    {
        $this->checked = true;
    }

    /**
     * @return bool
     * @throws RuntimeException
     */
    public function checkCalls(): bool
    {
        $this->checked = true;
        foreach ($this->getEventQueueCollection() as $eventDispatcherProxyData) {
            if (! $eventDispatcherProxyData->isEnoughEventCalls()) {
                throw new RuntimeException("{$eventDispatcherProxyData->getEventPlace()} have incorrect calls count");
            }
        }

        return true;
    }

    /**
     * @param EventSubscriberInterface $subscriber
     * @return void
     * @throws RuntimeException
     */
    public function removeSubscriber(EventSubscriberInterface $subscriber)
    {
        throw new RuntimeException("should not be implemented");
    }

    /**
     * Gets the listeners of a specific event or all listeners sorted by descending priority.
     *
     * @param string|null $eventName The name of the event
     *
     * @return array The event listeners for the specified event, or all event listeners by event name
     */
    public function getListeners(string $eventName = null)
    {
        return [];
    }

    /**
     * Dispatches an event to all registered listeners.
     *
     * For BC with Symfony 4, the $eventName argument is not declared explicitly on the
     * signature of the method. Implementations that are not bound by this BC constraint
     * MUST declare it explicitly, as allowed by PHP.
     *
     * @param object      $event The event to pass to the event handlers/listeners
     * @param string|null $eventName The name of the event to dispatch. If not supplied,
     *                               the class of $event should be used instead.
     *
     * @return object The passed $event MUST be returned
     * @throws LogicException
     * @throws RuntimeException
     */
    public function dispatch(object $event, string $eventName = null): object
    {
        if ($this->checked) {
            throw new RuntimeException("you cant dispatch after checked");
        }

        if (is_string($event)) {
            $tmp = $event;
            $event = $eventName;
            $eventName = $tmp;
        }

        if (! array_key_exists($eventName, $this->eventQueueCollection)) {
            throw new LogicException("event ({$eventName}) should called without definition");
        }

        /** @var EventDispatcherProxyData $eventProxy */
        $eventProxy = $this->eventQueueCollection[$eventName];

        $eventProxy->eventHit($event);

        return $event;
    }

    /**
     * Adds an event listener that listens on the specified events.
     *
     * @param string $eventName The event to listen on
     * @param EventDispatcherProxyData $listener The listener
     * @param int $priority The higher this value, the earlier an event
     *                            listener will be triggered in the chain (defaults to 0)
     * @throws RuntimeException
     */
    public function addListener($eventName, $listener, $priority = 0)
    {
        if ($this->checked) {
            throw new RuntimeException("do not add listeners after check of calls");
        }

        if (! $listener instanceof EventDispatcherProxyData) {
            throw new RuntimeException("not correct event listener for tests appeared");
        }

        if (array_key_exists($eventName, $this->getEventQueueCollection())) {
            throw new RuntimeException("listener {$eventName} already exists");
        }

        $this->eventQueueCollection[$eventName] = $listener;
    }

    /**
     * Adds an event subscriber.
     *
     * The subscriber is asked for all the events it is
     * interested in and added as a listener for these events.
     * @param EventSubscriberInterface $subscriber
     * @throws RuntimeException
     */
    public function addSubscriber(EventSubscriberInterface $subscriber)
    {
        throw new RuntimeException("should not be implemented");
    }

    /**
     * Removes an event listener from the specified events.
     *
     * @param string $eventName The event to remove a listener from
     * @param callable $listener The listener to remove
     */
    public function removeListener($eventName, $listener)
    {
        unset($this->eventQueueCollection[$eventName]);
    }

    /**
     * Gets the listener priority for a specific event.
     *
     * Returns null if the event or the listener does not exist.
     *
     * @param string $eventName The name of the event
     * @param callable $listener The listener
     *
     * @return void The event listener priority
     * @throws RuntimeException
     */
    public function getListenerPriority($eventName, $listener)
    {
        throw new RuntimeException("should not be implemented");
    }

    /**
     * Checks whether an event has any registered listeners.
     *
     * @param string|null $eventName The name of the event
     *
     * @return bool true if the specified event has any listeners, false otherwise
     */
    public function hasListeners(string $eventName = null)
    {
        return array_key_exists($eventName, $this->getEventQueueCollection());
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * ['eventName' => 'methodName']
     *  * ['eventName' => ['methodName', $priority]]
     *  * ['eventName' => [['methodName1', $priority], ['methodName2']]]
     *
     * @return void The event names to listen to
     * @throws RuntimeException
     */
    public static function getSubscribedEvents()
    {
        throw new RuntimeException("should not be implemented");
    }

    /**
     * @return EventDispatcherProxyData[]
     */
    protected function getEventQueueCollection(): array
    {
        return $this->eventQueueCollection;
    }

    /**
     * @return bool
     */
    protected function isChecked(): bool
    {
        return $this->checked;
    }

    /**
     * @throws RuntimeException
     */
    public function __destruct()
    {
        if ($this->isChecked() === false && count($this->getEventQueueCollection())) {
            throw new RuntimeException("you should check calls before destructing");
        }
    }
}
