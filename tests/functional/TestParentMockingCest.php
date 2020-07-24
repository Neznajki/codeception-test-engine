<?php
declare(strict_types=1);

namespace Tests\TestsEngine\functional;

use AspectMock\Test;
use FunctionalTester;
use Tests\TestsEngine\Code\TestParentMocking;

/**
 * Class TestParentMockingCest
 * @package Tests\TestsEngine\functional
 */
class TestParentMockingCest
{

    public function testMeMocked(FunctionalTester $I)
    {
        $class = new TestParentMocking();
        Test::clean();
        Test::double(TestParentMocking::class, ['meMocked' => null]);

        $class->meMocked();
        $I->assertTrue(true);
    }

}
