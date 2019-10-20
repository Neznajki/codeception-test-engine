<?php
declare(strict_types=1);

namespace Tests\TestsEngine\functional\Traits;

use FunctionalTester;
use LogicException;
use RuntimeException;
use Symfony\Component\EventDispatcher\Event;
use Tests\Neznajka\Codeception\Engine\Abstraction\AbstractFunctionalSymfonyCodeceptionTest;
use Tests\Neznajka\Codeception\Engine\Traits\EventDispatchingTrait;
use Tests\Neznajka\Codeception\Engine\ValueObject\EventDispatcherProxyData;

/**
 * Class EventDispatchingTraitCest
 * @package Tests\TestsEngine\functional\Traits
 */
class EventDispatchingTraitCest extends AbstractFunctionalSymfonyCodeceptionTest
{
    use EventDispatchingTrait;

    private $expectException= false;

    /**
     * @param FunctionalTester $I
     * @throws LogicException
     * @throws RuntimeException
     * @depends testProxyException
     */
    public function testProxySuccess(FunctionalTester $I)
    {
        $eventNameMock = $this->getString();
        $proxy         = $this->getEventDispatcherProxy();
        $event = new Event();

        $proxyData = new EventDispatcherProxyData($eventNameMock);
        $proxyData->setEventBody(
            function ($incomingEvent) use ($event, $I) {
                $I->assertSame($event, $incomingEvent);
            }
        );

        $proxy->addListener($eventNameMock, $proxyData);
        $proxy->dispatch($eventNameMock, $event);
    }

    /**
     * @param FunctionalTester $I
     * @throws RuntimeException
     */
    public function testProxyException(FunctionalTester $I)
    {
        $eventNameMock = $this->getString();
        $proxy         = $this->getEventDispatcherProxy();
        $proxyData = new EventDispatcherProxyData($eventNameMock);
        $proxy->addListener($eventNameMock, $proxyData);

        $this->expectException = false;

        $self = $this;
        $I->expectThrowable(
            RuntimeException::class,
            function () use ($self, $I) {
                $self->_after($I);
            }
        );
    }
}
