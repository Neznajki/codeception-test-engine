<?php /** @noinspection SqlDialectInspection */
/** @noinspection SqlNoDataSourceInspection */
/** @noinspection SqlResolve */
declare(strict_types=1);

namespace Tests\Neznajka\Codeception\Engine\Service;

use AspectMock\Test;
use Exception;
use LogicException;
use Tests\Neznajka\Codeception\Engine\Objects\MysqlResultCollection;
use Tests\Neznajka\Codeception\Engine\Objects\MysqlResultMock;
use Tests\Neznajka\Codeception\Engine\StaticAccess\SQL;

/**
 * NOT FULLY TESTED
 *
 * Class QueryMatcherService
 * @package Tests\Neznajka\Codeception\Engine\Service
 */
class QueryMatcherService
{
    /** @var int[]|MysqlResultCollection[] */
    protected $collection = [];
    /** @var int[] */
    protected $executedQuery = [];
    /** @var int[] */
    protected $expectExecution = [];

    /**
     * @return bool
     * @throws LogicException
     */
    public function isExpectationsMet(): bool
    {
        foreach ($this->expectExecution as $key => $count) {
            if (empty($this->executedQuery[$key])) {
                throw new LogicException("query not executed {$key}");
            }

            $have   = $this->executedQuery[$key];
            $expect = $this->expectExecution[$key];
            if ($expect != $have) {
                throw new LogicException("query execution count does not mach (expected {$expect}, have {$have}) {$key}");
            }
        }

        return true;
    }

    /**
     * @param string $table
     * @param int $count
     * @return $this
     * @throws LogicException
     * @deprecated please use only for legacy code using tcl database
     */
    public function addCountTableRecords(string $table, int $count): self
    {
        $this->addItemToCollection($table, $count, 1);

        return $this;
    }

    /**
     * @param string $table
     * @param array $functionValues
     * @param int $expectedExecutions
     * @throws LogicException
     */
    public function addRecordUpdateStatement(string $table, array $functionValues, int $expectedExecutions = 1)
    {
        $this->addItemToCollection($this->getSqlForRecordUpdate($table, $functionValues), 1, $expectedExecutions);
    }

    /**
     * @param string $table
     * @param array $functionValues
     * @param int $expectedExecutions
     * @throws LogicException
     */
    public function addRecordInsertDuplicateIgnoreStatement(
        string $table,
        array $functionValues,
        int $expectedExecutions = 1
    ) {
        $query = $this->getSqlForRecordInsertOnDuplicateIgnore($table, $functionValues);

        $this->addItemToCollection($query, 1, $expectedExecutions);
    }

    /**
     * @param string $table
     * @param array $functionValues
     * @param int $expectedExecutions
     * @throws LogicException
     */
    public function addRecordInsertIgnoreStatement(
        string $table,
        array $functionValues,
        int $expectedExecutions = 1
    ) {
        $query = $this->getSqlForRecordInsertIgnore($table, $functionValues);

        $this->addItemToCollection($query, 1, $expectedExecutions);
    }

    /**
     * @param string $query
     * @param int $affectedRows
     * @param int $expectedExecutions
     * @return $this
     * @throws LogicException
     */
    public function addUpdateQueryStatement(string $query, int $affectedRows = 1, int $expectedExecutions = 1): self
    {
        $this->addItemToCollection($query, $affectedRows, $expectedExecutions);

        return $this;
    }

    /**
     * @param string $query
     * @param MysqlResultMock $result
     * @param int $executions
     * @return $this
     * @throws LogicException
     */
    public function addQueryResult(string $query, MysqlResultMock $result, int $executions = 1): self
    {

        for ($i=0; $i< $executions; $i++) {
            $this->addItemToCollection($query, $result, 1, true);
        }

        return $this;
    }

    /**
     * @param string $query
     * @return MysqlResultMock|int|MysqlResultCollection
     * @throws LogicException
     * @throws Exception
     */
    public function getQueryResult(string $query)
    {
        $key = $this->getQueryIndex($query);

        if (array_key_exists($key, $this->collection) === false) {
            throw new LogicException("sql not found {$query}");
        }

        $executionCount            = $this->executedQuery[$key] ?? 0;
        $this->executedQuery[$key] = ++$executionCount;

        $result = &$this->collection[$key];
        if (is_int($result)) {
            $this->defineRelatedClassElementCount($result);

            return $result;
        }

        if ($result instanceof MysqlResultCollection) {
            return $result->getNextResult($key);
        }

        return $result;
    }

    /**
     * @param string $query
     * @param $data
     * @param int $expectedExecutions
     * @param bool $allowMultiple
     * @return $this
     * @throws LogicException
     */
    protected function addItemToCollection(string $query, $data, int $expectedExecutions, bool $allowMultiple = false): self
    {
        $key = $this->getQueryIndex($query);

        if ($allowMultiple) {
            if (empty($this->collection[$key])) {
                $this->collection[$key] = new MysqlResultCollection();
            }
        } elseif (array_key_exists($key, $this->collection)) {
            throw new LogicException("query already exists ({$query})");
        } else {
            $this->collection[$key] = null;
        }

        $resultsContainer = $this->collection[$key];

        if ($resultsContainer instanceof MysqlResultCollection) {
            $resultsContainer->addToCollection($data);
            $this->expectExecution[$key] = $resultsContainer->getExpectedExecutionCount();

            return $this;
        }

        $this->expectExecution[$key] = $expectedExecutions;
        $this->collection[$key] = $data;

        return $this;
    }

    /**
     * @param string $query
     * @return string
     */
    protected function getQueryIndex(string $query): string
    {
        return SQL::cleanSqlFormattingSpaces($query);
    }

    /**
     * @param string $table
     * @param array $functionValues
     * @return string
     * @throws LogicException
     */
    protected function getSqlForRecordUpdate(string $table, array $functionValues): string
    {
        if (empty($functionValues['id'])) {
            throw new LogicException("record update without ID is impossible");
        }

        $id = $functionValues['id'];
        unset($functionValues['id']);

        if (empty($functionValues)) {
            throw new LogicException("update values should include something additionally to id");
        }

        $updateValues = [];
        foreach ($functionValues as $field => $value) {
            $value          = $this->wrapValue($value);
            $field          = $this->getEscapedValue($field);
            $updateValues[] = "`{$field}` = {$value}";
        }

        $updatePart = implode(', ', $updateValues);
        $result     = "UPDATE `{$table}` SET {$updatePart} WHERE `id` = '{$id}'";

        return $result;
    }

    /**
     * @param string $table
     * @param array $functionValues
     * @return string
     * @throws LogicException
     */
    protected function getSqlForRecordInsertOnDuplicateIgnore(string $table, array $functionValues): string
    {
        $insertDataPart = $this->getInsertDataPart($functionValues);
        $result         = "INSERT INTO `{$table}` {$insertDataPart} ON DUPLICATE KEY UPDATE `id` = `id`";

        return $result;
    }

    /**
     * @param string $table
     * @param array $functionValues
     * @return string
     * @throws LogicException
     */
    protected function getSqlForRecordInsertIgnore(string $table, array $functionValues): string
    {
        $insertDataPart = $this->getInsertDataPart($functionValues);
        $result         = "INSERT IGNORE INTO {$table} {$insertDataPart}";

        return $result;
    }

    /**
     * @param $value
     * @return string
     */
    protected function wrapValue($value): string
    {
        $functionsNotWrap = ['NOW()'];
        if ($value !== 0 && in_array($value, $functionsNotWrap)) {
            return $value;
        }
        $result = $this->getEscapedValue($value);

        return "'{$result}'";
    }

    /**
     * @param array $functionValues
     * @return string
     * @throws LogicException
     */
    protected function getInsertDataPart(array $functionValues): string
    {
        unset($functionValues['id']);

        if (empty($functionValues)) {
            throw new LogicException("update values should include something additionally to id");
        }

        $updateValues = [];
        $updateFields = [];
        foreach ($functionValues as $field => $value) {
            $value = $this->wrapValue($value);
            $field = $this->getEscapedValue($field);

            $updateValues[] = "{$value}";
            $updateFields[] = "`{$field}`";
        }

        $valuePart = implode(', ', $updateValues);
        $fieldPart = implode(', ', $updateFields);

        $insertDataPart = "({$fieldPart}) VALUES ({$valuePart})";

        return $insertDataPart;
    }

    /**
     * @param $field
     * @return mixed
     */
    protected function getEscapedValue($field)
    {
        return preg_replace("/[\\\\\"']/", "\\\\$0", $field);
    }

    /**
     * could be moved to some wrapped class without multiple implementations
     * @param int $result
     * @throws Exception
     */
    protected function defineRelatedClassElementCount(int $result)
    {
        if (class_exists('TCL\\DataBase_Db')) {
            Test::func('TCL', 'my_affected_rows', $result);
        }

        if (class_exists('Dyninno\CorePlugin\Database\SQL')) {
            Test::double('Dyninno\CorePlugin\Database\SQL', ['affectedRows' => $result]);
            Test::double('Dyninno\CorePlugin\Database\Database', ['affectedRows' => $result]);
        }
    }
}

