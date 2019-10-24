<?php
declare(strict_types=1);

namespace Tests\Neznajka\Codeception\Engine\Objects\Doctrine;

use Doctrine\ORM\Query\Exec\SingleSelectExecutor;

/**
 * TODO make me working // Call to undefined method Tests\Neznajka\Codeception\Engine\Objects\MysqlResultMock::fetch()
 * Class DoctrineQueryTestCall
 * @package Tests\Neznajka\Codeception\Engine\Objects\Doctrine
 */
class DoctrineQuerySelectTestCall extends AbstractDoctrineTestCall
{
    /** @var string */
    protected $query;

    public function __construct(string $query, array $arguments, $response)
    {
        parent::__construct('execute', $arguments, $response);
        $this->query = $query;
    }

    /**
     * @param array $arguments
     * @param SingleSelectExecutor $class
     * @return bool
     */
    protected function isArgumentsValid(array $arguments, $class): bool
    {
        return
            parent::isArgumentsValid($arguments[1], $class) &&
            $this->query === $class->getSqlStatements();
    }


    public function getTargetClassName(): string
    {
        return SingleSelectExecutor::class;
    }
}
