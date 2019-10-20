<?php /** @noinspection PhpUnused */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection SqlResolve */
/** @noinspection SqlNoDataSourceInspection */
declare(strict_types=1);

use Tests\Neznajka\Codeception\Engine\Objects\MysqlResultMock;
use Tests\Neznajka\Codeception\Engine\Service\QueryMatcherService;
use Tests\Neznajka\Codeception\Engine\Traits\RandomGenerationTrait;

class QueryMatcherServiceCest
{
    use RandomGenerationTrait;

    public function testAddUpdateQueryStatement(FunctionalTester $I)
    {
        $tableMock      = $this->getString();
        $field1         = $this->getString();
        $value1         = $this->getString();
        $queryParam     = "INSERT INTO `{$tableMock}` (`{$field1}`) VALUES ('{$value1}')";
        $expectingQuery = "
INSERT INTO `{$tableMock}` 
    (`{$field1}`) VALUES ('{$value1}')
";

        $queryMatcher    = new QueryMatcherService();
        $expectingResult = $this->getInt();
        $queryMatcher->addUpdateQueryStatement($expectingQuery, $expectingResult);

        $result = $queryMatcher->getQueryResult($queryParam);

        $I->assertEquals($expectingResult, $result);
    }

    public function testAddRecordInsertIgnoreStatement(FunctionalTester $I)
    {
        $tableMock  = $this->getString();
        $field1     = $this->getString();
        $value1     = $this->getString();
        $queryParam = "INSERT IGNORE INTO {$tableMock} (`{$field1}`) VALUES ('{$value1}')";

        $queryMatcher    = new QueryMatcherService();
        $expectingResult = 1;
        $queryMatcher->addRecordInsertIgnoreStatement($tableMock, [$field1 => $value1]);

        $result = $queryMatcher->getQueryResult($queryParam);

        $I->assertEquals($expectingResult, $result);
    }

    public function testAddCountTableRecords(FunctionalTester $I)
    {
        $tableMock = $this->getString();

        $queryMatcher    = new QueryMatcherService();
        $expectingResult = $this->getInt();
        /** @noinspection PhpDeprecationInspection */
        $queryMatcher->addCountTableRecords($tableMock, $expectingResult);

        $result = $queryMatcher->getQueryResult($tableMock);

        $I->assertEquals($expectingResult, $result);
    }

    public function testAddRecordUpdateStatement(FunctionalTester $I)
    {
        $tableMock  = $this->getString();
        $field1     = $this->getString();
        $value1     = $this->getString();
        $idField    = 'id';
        $idMock     = $this->getInt();
        $queryParam = "UPDATE `{$tableMock}` SET `{$field1}` = '{$value1}' WHERE `{$idField}` = '{$idMock}'";

        $queryMatcher    = new QueryMatcherService();
        $expectingResult = 1;
        $queryMatcher->addRecordUpdateStatement($tableMock, [$field1 => $value1, $idField => $idMock]);

        $result = $queryMatcher->getQueryResult($queryParam);

        $I->assertEquals($expectingResult, $result);
    }

    public function testAddRecordInsertDuplicateUpdateStatement(FunctionalTester $I)
    {
        $tableMock  = $this->getString();
        $field1     = $this->getString();
        $value1     = $this->getString();
        $idField    = 'id';
        $idMock     = $this->getInt();


        $queryParam = "INSERT INTO `{$tableMock}` (`{$field1}`) VALUES ('{$value1}') ";
        $queryParam .= "ON DUPLICATE KEY UPDATE `id` = `id`";

        $queryMatcher    = new QueryMatcherService();
        $expectingResult = 1;
        $queryMatcher->addRecordInsertDuplicateIgnoreStatement($tableMock, [$field1 => $value1, $idField => $idMock]);

        $result = $queryMatcher->getQueryResult($queryParam);

        $I->assertEquals($expectingResult, $result);
    }

    public function testAddQueryResult(FunctionalTester $I)
    {
        $tableMock      = $this->getString();
        $queryParam     = "SELECT * FROM {$tableMock}";
        $expectingQuery = "
SELECT *
FROM {$tableMock}
";

        $queryMatcher    = new QueryMatcherService();
        $expectingResult = new MysqlResultMock($this->getArray());
        $queryMatcher->addQueryResult($expectingQuery, $expectingResult);

        $result = $queryMatcher->getQueryResult($queryParam);

        $I->assertEquals($expectingResult, $result);
    }
}
