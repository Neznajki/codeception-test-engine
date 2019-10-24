<?php
declare(strict_types=1);

namespace Tests\Neznajka\Codeception\Engine\Objects\Doctrine;

use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Driver\AbstractMySQLDriver;
use Doctrine\DBAL\Driver\Connection;

/**
 * Class DoctrineTestDriver
 * @package Tests\Neznajka\Codeception\Engine\Objects
 */
class DoctrineTestDriver extends AbstractMySQLDriver
{

    /**
     * Attempts to create a connection with the database.
     *
     * @param array $params All connection parameters passed by the user.
     * @param string|null $username The username to use when connecting.
     * @param string|null $password The password to use when connecting.
     * @param array $driverOptions The driver options to use when connecting.
     *
     * @return Connection The database connection.
     * @throws DBALException
     */
    public function connect(array $params, $username = null, $password = null, array $driverOptions = [])
    {
        return new DoctrineTestConnection($params, $this);
    }

    /**
     * Gets the name of the driver.
     *
     * @return string The name of the driver.
     */
    public function getName()
    {
        return 'Doctrine Functional Testing Driver';
    }
}
