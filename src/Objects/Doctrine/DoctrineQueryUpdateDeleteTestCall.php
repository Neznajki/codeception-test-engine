<?php
declare(strict_types=1);

namespace Tests\Neznajka\Codeception\Engine\Objects\Doctrine;

use Doctrine\ORM\Query\Exec\SingleSelectExecutor;
use Doctrine\ORM\Query\Exec\SingleTableDeleteUpdateExecutor;

/**
 * Class DoctrineQueryTestCall
 * @package Tests\Neznajka\Codeception\Engine\Objects\Doctrine
 */
class DoctrineQueryUpdateDeleteTestCall extends AbstractDoctrineTestCall
{
    /** @var string */
    protected $query;

    /**
     * DoctrineQueryUpdateDeleteTestCall constructor.
     * @param string $query
     * @param array $arguments
     * @param int $affectedRows
     */
    public function __construct(string $query, array $arguments, int $affectedRows)
    {
        parent::__construct('execute', $arguments, $affectedRows);
        $this->query = $query;
    }

    /**
     * @return string
     */
    public function getTargetClassName(): string
    {
        return SingleTableDeleteUpdateExecutor::class;
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
}
