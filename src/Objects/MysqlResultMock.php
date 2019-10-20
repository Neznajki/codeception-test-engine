<?php /** @noinspection PhpUnused */
/** @noinspection PhpUnusedParameterInspection */
/** @noinspection PhpMissingParentConstructorInspection */
declare(strict_types=1);

namespace Tests\Neznajka\Codeception\Engine\Objects;

use ArrayAccess;
use Iterator;
use LogicException;
use stdClass;

/**
 * Class MysqlResultMock
 * @package Tests\Neznajka\Codeception\Engine\Objects
 */
class MysqlResultMock implements ArrayAccess, Iterator
{
    /** @var array|null */
    protected $data;
    /** @var array|null */
    protected $fetchingData;
    /** @var bool */
    protected $resultHit = false;

    /**
     * MysqlResultMock constructor.
     * @param array|null $data
     */
    public function __construct($data = null)
    {
        $this->fetchingData = $this->data = $data;
    }

    /**
     * @return bool
     */
    public function isResultHit(): bool
    {
        return $this->resultHit;
    }

    /**
     * @param bool $resultHit
     *
     * @return $this
     */
    public function setResultHit(bool $resultHit): self
    {
        $this->resultHit = $resultHit;

        return $this;
    }

    /**
     * @return bool|void
     */
    public function close()
    {
        return true;
    }

    /**
     * @return bool|void
     */
    public function free()
    {
        return true;
    }

    /**
     * @param int $offset
     * @return bool|void
     * @throws LogicException
     */
    public function data_seek($offset)
    {
        throw new LogicException("not implemented");
    }

    /**
     * @return object|void
     * @throws LogicException
     */
    public function fetch_field()
    {
        throw new LogicException("not implemented");
    }

    /**
     * @return array|void
     * @throws LogicException
     */
    public function fetch_fields()
    {
        throw new LogicException("not implemented");
    }

    /**
     * @param int $fieldnr
     * @return object|void
     * @throws LogicException
     */
    public function fetch_field_direct($fieldnr)
    {
        throw new LogicException("not implemented");
    }

    /**
     * @param null $resulttype
     * @return mixed|void
     * @throws LogicException
     */
    public function fetch_all($resulttype = null)
    {
        throw new LogicException("not implemented");
    }

    /**
     * @param int $resulttype
     * @return array|mixed
     */
    public function fetch_array($resulttype = MYSQLI_BOTH)
    {
        return array_values($this->data);
    }

    /**
     * @return array|null
     */
    public function fetch_assoc()
    {
        if ($this->fetchingData === null) {
            return null;
        }

        $current = current($this->fetchingData);
        next($this->fetchingData);

        return $current;
    }

    /**
     * @param string $class_name
     * @param array|null $params
     * @return object|stdClass
     */
    public function fetch_object($class_name = 'stdClass', array $params = null)
    {
        $assoc = $this->fetch_assoc();

        if (empty($assoc)) {
            return null;
        }

        return (object)$assoc;
    }

    /**
     * @return mixed
     */
    public function fetch_row()
    {
        return next($this->data);
    }

    /**
     * @param int $fieldnr
     * @return bool|void
     * @throws LogicException
     */
    public function field_seek($fieldnr)
    {
        throw new LogicException("not implemented");
    }

    /**
     * @throws LogicException
     */
    public function free_result()
    {
        throw new LogicException("not implemented");
    }

    /**
     * Whether a offset exists
     * @link https://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     */
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->data);
    }

    /**
     * Offset to retrieve
     * @link https://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($offset)
    {
        return $this->data[$offset];
    }

    /**
     * Offset to set
     * @link https://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetSet($offset, $value)
    {
        $this->data[$offset] = $value;
    }

    /**
     * Offset to unset
     * @link https://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetUnset($offset)
    {
        if ($this->offsetExists($offset)) {
            unset($this->data[$offset]);
        }
    }

    /**
     * Return the current element
     * @link https://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     * @since 5.0.0
     */
    public function current()
    {
        if (empty($this->data)) {
            return null;
        }

        return current($this->data);
    }

    /**
     * Move forward to next element
     * @link https://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function next()
    {
        if (empty($this->data)) {
            return null;
        }

        return next($this->data);
    }

    /**
     * Return the key of the current element
     * @link https://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     * @since 5.0.0
     */
    public function key()
    {
        if (empty($this->data)) {
            return null;
        }

        return key($this->data);
    }

    /**
     * Checks if current position is valid
     * @link https://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     * @since 5.0.0
     */
    public function valid()
    {
        if (empty($this->data)) {
            return false;
        }

        return key($this->data) !== null;
    }

    /**
     * Rewind the Iterator to the first element
     * @link https://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function rewind()
    {
        if ($this->data === null) {
            return;
        }

        reset($this->data);
    }
}
