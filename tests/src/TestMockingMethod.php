<?php
declare(strict_types=1);

namespace Tests\TestsEngine\Code;

use RuntimeException;

/**
 * Class TestMockingMethod
 * @package Tests\TestsEngine\Code
 */
class TestMockingMethod
{

    /**
     * @throws RuntimeException
     */
    public final function meMocked()
    {
        throw new RuntimeException("I should be mocked");
    }

    /**
     * @throws RuntimeException
     */
    public static final function meMockedStatic()
    {
        throw new RuntimeException("I should be mocked");
    }
}
