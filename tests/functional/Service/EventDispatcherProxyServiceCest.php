<?php /** @noinspection PhpUnused */
/** @noinspection PhpUnhandledExceptionInspection */
declare(strict_types=1);

use Symfony\Component\EventDispatcher\Event;
use Tests\Neznajka\Codeception\Engine\Service\EventDispatcherProxyService;
use Tests\Neznajka\Codeception\Engine\Traits\RandomGenerationTrait;
use Tests\Neznajka\Codeception\Engine\Traits\SymfonyKernelTrait;
use Tests\Neznajka\Codeception\Engine\ValueObject\EventDispatcherProxyData;
use Tests\TestsEngine\Code\TestSuccessException;

class EventDispatcherProxyServiceCest
{
    use SymfonyKernelTrait, RandomGenerationTrait;

    public function checkDispatcherFunctionality_case_custom_body(FunctionalTester $I)
    {
        $dispatcher = new EventDispatcherProxyService();
        $eventPlace = $this->getString();
        $eventDispatcherProxyData = new EventDispatcherProxyData(
            $eventPlace
        );

        $eventDispatcherProxyData->setEventBody(function () use ($dispatcher)
        {
            $dispatcher->checkCalls();

            throw new TestSuccessException("all fine");
        });

        $dispatcher->addListener($eventPlace, $eventDispatcherProxyData);

        try {
            $dispatcher->dispatch($eventPlace, new Event());
        } /** @noinspection PhpRedundantCatchClauseInspection */ catch (TestSuccessException $exception) {
            $I->assertTrue(true);
            return;
        }

        $I->assertTrue(false, "exception should be thrown");
    }

    public function checkDispatcherFunctionality_case_simple_calls(FunctionalTester $I)
    {
        $dispatcher = new EventDispatcherProxyService();
        $callCount = rand(5, 10);
        $eventPlace = $this->getString();
        $eventDispatcherProxyData = new EventDispatcherProxyData(
            $eventPlace,
            $callCount
        );

        $dispatcher->addListener($eventPlace, $eventDispatcherProxyData);

        for($i = 0; $i < $callCount; $i++) {
            $dispatcher->dispatch($eventPlace, new Event());
        }

        $dispatcher->checkCalls();
        $I->assertTrue(true, 'if not then exception');
    }
}
