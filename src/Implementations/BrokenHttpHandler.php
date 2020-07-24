<?php /** @noinspection PhpUnused */
/** @noinspection PhpUnusedParameterInspection */
declare(strict_types=1);


namespace Tests\Neznajka\Codeception\Engine\Implementations;

use /** @noinspection PhpUndefinedClassInspection */
    /** @noinspection PhpUndefinedNamespaceInspection */
    Dynatech\Libraries\Client\RequestHandler\HttpHandler;
use LogicException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;


/** @noinspection PhpUndefinedClassInspection */
/**
 * Class ExceptionHttpHandler
 * @package JsonRpcServiceTests
 */
class BrokenHttpHandler extends HttpHandler
{

    /**
     * @param RequestInterface $request
     * @return string
     * @throws LogicException
     */
    public function handleRequest(RequestInterface $request): string
    {
        throw new LogicException(sprintf("method should not be used %s", __METHOD__));
    }

    /**
     * @param array $requests
     * @param int $concurrent
     * @return array
     * @throws LogicException
     */
    public function batch(array $requests, int $concurrent): array
    {
        throw new LogicException(sprintf("method should not be used %s", __METHOD__));
    }

    /**
     * Create a new request.
     *
     * @param string $method The HTTP method associated with the request.
     * @param UriInterface|string $uri The URI associated with the request. If
     *     the value is a string, the factory MUST create a UriInterface
     *     instance based on it.
     *
     * @return RequestInterface
     * @throws LogicException
     */
    public function createRequest(string $method, $uri): RequestInterface
    {
        throw new LogicException(sprintf("method should not be used %s", __METHOD__));
    }
}
