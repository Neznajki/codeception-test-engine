<?php
declare(strict_types=1);

namespace Tests\Neznajka\Codeception\Engine\Implementations;

use Dyninno\DependencyInjection\AutoWiredContainer;
use Dyninno\DependencyInjection\Exceptions\AbstractDependencyInjectionException;

/**
 * Class TestCotnainer
 * @package JsonRpcServiceTests
 */
class TestContainer extends AutoWiredContainer
{
    /** @var array */
    protected $removedKeys = [];

    /** @var array */
    protected $testCollection = [];

    /**
     * @param string $id
     * @param $item
     */
    public function set(string $id, $item)
    {
        $this->testCollection[$id] = $item;
    }

    /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @return mixed Entry.
     * @throws AbstractDependencyInjectionException
     */
    public function get($id)
    {
        if (! $this->has($id)) {
            $this->set($id, $this->constructClass($id));
        }

        if ($this->has($id)) {
            return $this->testCollection[$id];
        }

        return null;
    }

    /**
     * Returns true if the container can return an entry for the given identifier.
     * Returns false otherwise.
     *
     * `has($id)` returning true does not mean that `get($id)` will not throw an exception.
     * It does however mean that `get($id)` will not throw a `NotFoundExceptionInterface`.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @return bool
     */
    public function has($id): bool
    {
        return array_key_exists($id, $this->testCollection);
    }

    /**
     * @param string $key
     * @throws AbstractDependencyInjectionException
     */
    public function remove(string $key)
    {
        if (! $this->has($key)) {
            return ;
        }

        $this->removedKeys[$key] = $this->get($key);
        unset($this->testCollection[$key]);
    }

    /**
     *
     */
    public function recover()
    {
        foreach ($this->removedKeys as $key => $value) {
            $this->set($key, $value);
        }

        $this->removedKeys = [];
    }
}
