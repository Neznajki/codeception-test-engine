<?php
declare(strict_types=1);

namespace Tests\Neznajka\Codeception\Engine\StaticAccess;

/**
 * Class SQL
 * @package Tests\Neznajka\Codeception\Engine\StaticAccess
 */
class SQL
{
    /**
     * @param string $sql
     * @return string
     */
    public static function cleanSqlFormattingSpaces(string $sql): string
    {
        return preg_replace('/\s+/', ' ', trim($sql));
    }
}
