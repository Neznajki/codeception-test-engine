<?php /** @noinspection PhpUnusedParameterInspection */
/** @noinspection PhpUnused */
/** @noinspection PhpUndefinedNamespaceInspection */
/** @noinspection PhpUndefinedClassInspection */
declare(strict_types=1);

namespace Tests\Neznajka\Codeception\Engine\Implementations;

use Fluent\Logger\Entity;
use Fluent\Logger\FluentLogger;
use Fluent\Logger\JsonPacker;
use Fluent\Logger\PackerInterface;

/**
 * Class DataLakeNull
 * @package Tests\Neznajka\Codeception\Engine\Implementations
 */
class DataLakeNull extends FluentLogger
{
    /** @noinspection PhpMissingParentConstructorInspection */
    /**
     * DataLakeNull constructor.
     * @param string $host
     * @param int $port
     * @param array $options
     * @param PackerInterface|null $packer
     */
    public function __construct(
        $host = FluentLogger::DEFAULT_ADDRESS,
        $port = FluentLogger::DEFAULT_LISTEN_PORT,
        array $options = [],
        PackerInterface $packer = null
    ) {
    }

    /**
     * @param FluentLogger $logger
     * @param Entity $entity
     * @param void $error
     */
    public function defaultErrorHandler(FluentLogger $logger, Entity $entity, $error)
    {
    }

    /**
     * @param Entity $entity
     * @param void $error
     */
    protected function processError(Entity $entity, $error)
    {
    }

    /**
     * @param callable $callable
     * @return bool
     */
    public function registerErrorHandler($callable)
    {
        return true;
    }

    public function unregisterErrorHandler()
    {
    }

    /**
     * @param $host
     * @param $port
     * @return string
     */
    public static function getTransportUri($host, $port)
    {
        return '';
    }

    /**
     * @param PackerInterface $packer
     * @return PackerInterface
     */
    public function setPacker(PackerInterface $packer)
    {
        return $packer;
    }

    /**
     * @return JsonPacker|PackerInterface|null
     */
    public function getPacker()
    {
        return null;
    }

    /**
     * @param array $options
     */
    public function mergeOptions(array $options)
    {
    }

    /**
     * @param array $options
     */
    public function setOptions(array $options)
    {
    }

    /**
     * @param string $host
     * @param int $port
     * @param array $options
     * @return FluentLogger|null
     */
    public static function open(
        $host = FluentLogger::DEFAULT_ADDRESS,
        $port = FluentLogger::DEFAULT_LISTEN_PORT,
        array $options = []
    ) {
        return null;
    }

    public static function clearInstances()
    {
    }

    protected function connect()
    {
    }

    protected function reconnect()
    {
    }

    /**
     * @param string $tag
     * @param array $data
     * @return bool|null
     */
    public function post($tag, array $data)
    {
        return null;
    }

    /**
     * @param Entity $entity
     * @return bool|null
     */
    public function post2(Entity $entity)
    {
        return null;
    }

    /**
     * @param Entity $entity
     * @return bool|null
     */
    protected function postImpl(Entity $entity)
    {
        return null;
    }/** @noinspection SpellCheckingInspection */

    /**
     * @param int $base
     * @param int $attempt
     */
    public function backoffExponential($base, $attempt)
    {
    }

    /**
     * @param $buffer
     * @return mixed|null
     */
    protected function write($buffer)
    {
        return null;
    }

    public function close()
    {
    }

    public function __destruct()
    {
    }

    /**
     * @param $key
     * @param null $default
     * @return mixed|null
     */
    public function getOption($key, $default = null)
    {
        return null;
    }

}

