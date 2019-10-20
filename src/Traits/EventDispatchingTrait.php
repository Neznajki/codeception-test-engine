<?php
declare(strict_types=1);

namespace Tests\Neznajka\Codeception\Engine\Traits;


use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Tests\Neznajka\Codeception\Engine\Objects\CallableAction;
use Tests\Neznajka\Codeception\Engine\Service\EventDispatcherProxyService;

/**
 * Trait EventDispatchingTrait
 * @package Tests\Neznajka\Codeception\Engine\Traits
 * @method Container getContainer()
 */
trait EventDispatchingTrait
{
    use AfterResolverTrait, BeforeResolverTrait;

    /** @var EventDispatcherProxyService */
    protected $eventDispatcherProxy;

    /**
     * @return EventDispatcherProxyService
     */
    protected function getEventDispatcherProxy(): EventDispatcherProxyService
    {
        if ($this->eventDispatcherProxy === null) {
            $this->initProxy();
        }

        return $this->eventDispatcherProxy;
    }

    /**
     *
     */
    private function initProxy()
    {
        $proxyService               = new EventDispatcherProxyService();
        $this->eventDispatcherProxy = $proxyService;

        if (method_exists($this, 'getContainer')) {
            $this->getContainer()->set(EventDispatcherInterface::class, $this->eventDispatcherProxy);
        }

        $this->addAfterAction(new CallableAction([$this->eventDispatcherProxy, 'checkCalls']));
        $self = $this;

        $this->addBeforeAction(new CallableAction(function () use ($self) {
            $self->eventDispatcherProxy = null;
        }));
    }
}
