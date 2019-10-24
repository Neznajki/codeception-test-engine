<?php
declare(strict_types=1);

namespace Tests\Neznajka\Codeception\Engine\Objects\Doctrine;

use AspectMock\Test;
use Doctrine\Bundle\DoctrineBundle\ConnectionFactory;
use Doctrine\Common\EventManager;
use Doctrine\DBAL\Platforms\MySqlPlatform;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Exception;

/**
 * Class DoctrineTestEngine
 * @package Tests\Neznajka\Codeception\Engine\Objects\Doctrine
 */
class DoctrineTestEngine
{
    /** @var DoctrineTestCallReplaceCollection */
    protected static $callCollection;

    /**
     * @throws Exception
     */
    public static function disableExecutionDoctrine32()
    {
        Test::double(
            ConnectionFactory::class,
            [
                'createConnection' => function (
                    array $params,
                    \Doctrine\DBAL\Configuration $config = null,
                    EventManager $eventManager = null
                ) {

                    $driver = new DoctrineTestDriver();
                    if (empty($params['platform'])) {
                        $params['platform'] = new MySqlPlatform();
                    }

                    return new DoctrineTestConnection(
                        $params,
                        $driver,
                        $config,
                        $eventManager
                    );
                },
            ]
        );

        Test::double(
            EntityManager::class,
            [
                'create' => function ($conn, Configuration $config) {
                    return new DoctrineTestEntityManager($conn, $config);
                },
            ]
        );

        self::$callCollection = new DoctrineTestCallReplaceCollection();
    }

    /**
     * @param AbstractDoctrineTestCall $doctrineTestCallReplace
     * @throws Exception
     */
    public static function addExpectingCall(AbstractDoctrineTestCall $doctrineTestCallReplace)
    {
        self::$callCollection->addCallReplace($doctrineTestCallReplace);
    }
}
