<?php
declare(strict_types=1);

namespace Tests\Neznajka\Codeception\Engine\Objects\Doctrine;

use Doctrine\Common\EventManager;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;

/**
 * Class DoctrineTestEntityManager
 * @package Tests\Neznajka\Codeception\Engine\Objects
 */
class DoctrineTestEntityManager extends EntityManager
{
    public function __construct(Connection $conn, Configuration $config)
    {
        $eventManager = new EventManager();

        parent::__construct($conn, $config, $eventManager);
    }
}
