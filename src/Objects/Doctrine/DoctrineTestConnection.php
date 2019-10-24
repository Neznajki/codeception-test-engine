<?php
declare(strict_types=1);

namespace Tests\Neznajka\Codeception\Engine\Objects\Doctrine;

use Doctrine\Common\EventManager;
use Doctrine\DBAL\Cache\QueryCacheProfile;
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver;
use RuntimeException;

/**
 * Class DoctrineTestConnection
 * @package Tests\Neznajka\Codeception\Engine\Objects
 */
class DoctrineTestConnection extends Connection
{
    /**
     * DoctrineTestConnection constructor.
     * @param array $params
     * @param Driver $driver
     * @param Configuration|null $config
     * @param EventManager|null $eventManager
     * @throws \Doctrine\DBAL\DBALException
     */
    public function __construct(
        array $params,
        Driver $driver,
        Configuration $config = null,
        EventManager $eventManager = null
    ) {
        parent::__construct(
            $params,
            $driver,
            $config,
            $eventManager
        );
    }

    /**
     * @param string $query
     * @param array $params
     * @param array $types
     * @param QueryCacheProfile|null $qcp
     * @return Driver\Statement|void
     * @throws RuntimeException
     */
    public function executeQuery($query, array $params = [], $types = [], QueryCacheProfile $qcp = null)
    {
        throw new RuntimeException("query should be not executed please mock functionality {$query}");
    }

    /**
     * @param string $statement
     * @return int
     * @throws \Doctrine\DBAL\DBALException
     */
    public function exec($statement)
    {
        return parent::exec($statement);
    }

}
