<?php
declare(strict_types=1);

namespace Tests\Neznajka\Codeception\Engine\Objects;

use RuntimeException;

/**
 * Class MysqlResultCollection
 * @package Tests\Neznajka\Codeception\Engine\Objects
 */
class MysqlResultCollection
{
    /** @var MysqlResultMock[] */
    protected $collection = [];

    /**
     * @param MysqlResultMock $resultMock
     * @return $this
     */
    public function addToCollection(MysqlResultMock $resultMock): self
    {
        $this->collection[] = $resultMock;

        return $this;
    }

    /**
     * @param string $query
     * @return MysqlResultMock
     * @throws RuntimeException
     */
    public function getNextResult(string $query): MysqlResultMock
    {
        foreach ($this->collection as $resultMock) {
            if ($resultMock->isResultHit() === false) {
                $resultMock->setResultHit(true);

                return $resultMock;
            }
        }

        throw new RuntimeException("all ({$query}) results got hit");
    }

    /**
     * @return int
     */
    public function getExpectedExecutionCount(): int
    {
        return count($this->collection);
    }
}
