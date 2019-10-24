<?php /** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */
declare(strict_types=1);

namespace Tests\TestsEngine\functional\Objects\ResponseTester;

use Codeception\Example;
use DateTime;
use FunctionalTester;
use PHPUnit\Framework\ExpectationFailedException;
use Tests\Neznajka\Codeception\Engine\Objects\ResponseTester\ArrayDateTimeNow;
use Tests\Neznajka\Codeception\Engine\Objects\ResponseTester\ArrayDateTimeValue;
use Tests\Neznajka\Codeception\Engine\Objects\ResponseTester\ArrayStringExpectedLength;
use Tests\Neznajka\Codeception\Engine\Objects\ResponseTester\ArrayUnknownValue;
use Tests\Neznajka\Codeception\Engine\Objects\ResponseTester\TestResponseRecursiveChecker;

class TestResponseRecursiveCheckerCest
{

    /**
     * @dataProvider dataProvider
     * @param FunctionalTester $I
     * @param Example $dataProvider
     */
    public function testIsResponseExpected(FunctionalTester $I, Example $dataProvider)
    {
        $class = new TestResponseRecursiveChecker(
            $I,
            $dataProvider->offsetGet('expecting')
        );

        if (
            $dataProvider->offsetExists('success') &&
            $dataProvider->offsetGet('success')
        ) {
            $class->isResponseExpected($dataProvider->offsetGet('response'));
        } else {
            $I->expectException(
                ExpectationFailedException::class,
                function () use ($I, $dataProvider, $class) {
                    $class->isResponseExpected($dataProvider->offsetGet('response'));
                }
            );
        }
    }

    public function dataProvider()
    {
        $nowDateTimeCheck = new ArrayDateTimeValue(new ArrayDateTimeNow());
        sleep(1);

        return [
            [
                'response'  => [],
                'expecting' => [],
                'success'   => true,
            ],
            [
                'response'  => ['test'],
                'expecting' => [new ArrayUnknownValue()],
                'success'   => true,
            ],
            [
                'response'  => ['test'],
                'expecting' => [new ArrayStringExpectedLength(4)],
                'success'   => true,
            ],
            [
                'response'  => ['test', 'cool'],
                'expecting' => [ 1 => 'cool', 0 => 'test'],
                'success'   => true,
            ],
            [
                'response'  => ['test'],
                'expecting' => [],
                'success'   => false,
            ],
            [
                'response'  => ['test'],
                'expecting' => ['cool' => 'test'],
                'success'   => false,
            ],
            [
                'expecting' => [$nowDateTimeCheck],
                'response'  => [new DateTime()],
                'success'   => true,
            ],
        ];
    }
}
