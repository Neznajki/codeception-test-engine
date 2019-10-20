<?php /** @noinspection PhpRedundantCatchClauseInspection */

/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\TestsEngine\functional\Extension;

use FunctionalTester;
use LogicException;
use Tests\TestsEngine\Code\TestMockingMethod;

class MethodDisableExtensionCest
{

    public function testDisableFunctions(FunctionalTester $I)
    {
        $class = new TestMockingMethod();

        try {
            $class->meMocked();
        } catch (LogicException $exception) {
            return $I->assertTrue(true);
        }

        return $I->assertTrue(false, 'exception expected');
    }

    public function testDisableStaticFunctions(FunctionalTester $I)
    {
        $class = new TestMockingMethod();

        try {
            $class::meMockedStatic();
        } catch (LogicException $exception) {
            return $I->assertTrue(true);
        }

        return $I->assertTrue(false, 'exception expected');
    }
}
