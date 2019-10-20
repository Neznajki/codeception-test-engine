<?php
declare(strict_types=1);

namespace Tests\Neznajka\Codeception\Engine\ValueObject;

/**
 * Class EventDispatcherProxyData
 * @package Tests\Neznajka\Codeception\Engine\ValueObject
 */
class EventDispatcherProxyData
{
    /** @var callable */
    protected $eventBody;
    /** @var string */
    protected $eventPlace;
    /** @var int */
    protected $calledTimes = 0;
    /** @var int */
    protected $exceptedCalls = 0;

    /**
     * EventDispatcherProxyData constructor.
     * @param string $eventPlace
     * @param int $exceptedCalls
     */
    public function __construct(string $eventPlace, int $exceptedCalls = 1)
    {
        $this->eventPlace = $eventPlace;
        $this->exceptedCalls = $exceptedCalls;
    }

    /**
     * @param callable $eventBody
     *
     * @return $this
     */
    public function setEventBody(callable $eventBody): self
    {
        $this->eventBody = $eventBody;

        return $this;
    }

    /**
     * @return callable
     */
    public function getEventBody(): callable
    {
        if ($this->eventBody === null) {
            $this->eventBody = function(... $arguments) { };
        }

        return $this->eventBody;
    }

    /**
     * @return string
     */
    public function getEventPlace(): string
    {
        return $this->eventPlace;
    }

    /**
     * @param $event
     */
    public function eventHit($event)
    {
        $this->calledTimes++;
        $eventFunction = $this->getEventBody();
        $eventFunction($event);
    }

    /**
     * @return bool
     */
    public function isEnoughEventCalls(): bool
    {
        return $this->calledTimes === $this->exceptedCalls;
    }

    /**
     * @return int
     */
    protected function getCalledTimes(): int
    {
        return $this->calledTimes;
    }

    /**
     * @return int
     */
    protected function getExceptedCalls(): int
    {
        return $this->exceptedCalls;
    }
}
